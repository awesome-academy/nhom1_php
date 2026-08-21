window.adminOrderManager = function () {
    return {
        loading: true,
        updating: false,
        orders: [],
        meta: { current_page: 1, last_page: 1, total: 0, per_page: 10 },
        filters: { status: "", search: "", per_page: 10 },
        metrics: {
            total: 0,
            pending: 0,
            confirmed: 0,
            preparing: 0,
            completed: 0,
            cancelled: 0,
        },
        detailOrder: null,
        notice: { type: "success", message: "" },

        init() {
            this.fetchOrders(1);
        },

        setFilter(status) {
            this.filters.status = status;
            this.fetchOrders(1);
        },

        filteredOrders() {
            if (!this.filters.search) return this.orders;
            const q = this.filters.search.toLowerCase().trim();
            return this.orders.filter(
                (o) =>
                    String(o.id).includes(q) ||
                    (o.user?.name && o.user.name.toLowerCase().includes(q)) ||
                    (o.user?.email && o.user.email.toLowerCase().includes(q)) ||
                    (o.user?.phone && o.user.phone.includes(q)) ||
                    (o.phone && o.phone.includes(q)),
            );
        },

        async fetchOrders(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page,
                    per_page: this.filters.per_page,
                });
                if (this.filters.status)
                    params.set("status", this.filters.status);

                const res = await fetch(`/admin/orders?${params.toString()}`, {
                    headers: { Accept: "application/json" },
                });
                const json = await res.json();
                this.orders = json.data ?? [];
                this.meta = json.meta ?? {
                    current_page: 1,
                    last_page: 1,
                    total: this.orders.length,
                    per_page: this.filters.per_page,
                };
                this.calculateMetrics();
            } catch (e) {
                this.showNotice("error", "Không thể tải danh sách đơn hàng.");
            } finally {
                this.loading = false;
            }
        },

        calculateMetrics() {
            const counts = {
                pending: 0,
                confirmed: 0,
                preparing: 0,
                completed: 0,
                cancelled: 0,
            };
            this.orders.forEach((o) => {
                const s = o.status?.value ?? o.status;
                if (counts[s] !== undefined) counts[s]++;
            });
            this.metrics = {
                total: this.meta.total || this.orders.length,
                ...counts,
            };
        },

        async openDetail(order) {
            this.detailOrder = order;
            try {
                const res = await fetch(`/admin/orders/${order.id}`, {
                    headers: { Accept: "application/json" },
                });
                const json = await res.json();
                this.detailOrder = json.data ?? json;
            } catch (e) {
                this.showNotice("error", "Không thể tải chi tiết đơn hàng.");
            }
        },

        closeDetail() {
            this.detailOrder = null;
        },

        async updateStatus(order, status) {
            if (
                status === "cancelled" &&
                !confirm(
                    "Bạn có chắc chắn muốn huỷ đơn hàng này? Tồn kho sẽ được khôi phục.",
                )
            ) {
                return;
            }

            this.updating = true;
            try {
                const res = await fetch(`/admin/orders/${order.id}/status`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                    },
                    body: JSON.stringify({ status }),
                });
                const json = await res.json();

                if (res.status === 422) {
                    this.showNotice(
                        "error",
                        Object.values(json.errors ?? {}).flat()[0] ??
                            "Không thể cập nhật trạng thái.",
                    );
                    return;
                }
                if (!res.ok) throw new Error("request-failed");

                this.detailOrder = json.data ?? json;
                this.showNotice(
                    "success",
                    "Đã cập nhật trạng thái đơn hàng thành công.",
                );
                await this.fetchOrders(this.meta.current_page);
            } catch (e) {
                this.showNotice(
                    "error",
                    "Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.",
                );
            } finally {
                this.updating = false;
            }
        },

        allowedTransitions(status) {
            const val = status?.value ?? status;
            const map = {
                pending: ["confirmed", "cancelled"],
                confirmed: ["preparing", "cancelled"],
                preparing: ["completed"],
                completed: [],
                cancelled: [],
            };
            return map[val] ?? [];
        },

        nextPrimaryAction(status) {
            const val = status?.value ?? status;
            const actionMap = {
                pending: {
                    nextStatus: "confirmed",
                    btnLabel: "Xác nhận đơn",
                    btnClass: "bg-sky-600 hover:bg-sky-700",
                },
                confirmed: {
                    nextStatus: "preparing",
                    btnLabel: "Làm món",
                    btnClass: "bg-indigo-600 hover:bg-indigo-700",
                },
                preparing: {
                    nextStatus: "completed",
                    btnLabel: "Hoàn thành",
                    btnClass: "bg-emerald-600 hover:bg-emerald-700",
                },
            };
            return actionMap[val] ?? null;
        },

        transitionActionLabel(status) {
            const labels = {
                confirmed: "✓ Xác nhận tiếp nhận",
                preparing: "☕ Bắt đầu chuẩn bị món",
                completed: "★ Đánh dấu hoàn thành",
                cancelled: "✕ Huỷ đơn hàng",
            };
            return labels[status] ?? status;
        },

        transitionButtonClass(status) {
            if (status === "cancelled")
                return "border border-rose-200 bg-white text-rose-600 hover:bg-rose-50";
            if (status === "completed")
                return "bg-emerald-600 text-white hover:bg-emerald-700 shadow-xs";
            if (status === "preparing")
                return "bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs";
            return "bg-[#2B1E19] text-[#FAF5F1] hover:bg-[#B38352] shadow-xs";
        },

        statusInfo(status) {
            const val = status?.value ?? status;
            const map = {
                pending: {
                    label: "Chờ xác nhận",
                    badgeClass: "bg-amber-50 text-amber-800 border-amber-200",
                    dotClass: "bg-amber-500",
                },
                confirmed: {
                    label: "Đã tiếp nhận",
                    badgeClass: "bg-sky-50 text-sky-800 border-sky-200",
                    dotClass: "bg-sky-500",
                },
                preparing: {
                    label: "Đang làm món",
                    badgeClass:
                        "bg-indigo-50 text-indigo-800 border-indigo-200",
                    dotClass: "bg-indigo-500 animate-pulse",
                },
                completed: {
                    label: "Hoàn thành",
                    badgeClass:
                        "bg-emerald-50 text-emerald-800 border-emerald-200",
                    dotClass: "bg-emerald-500",
                },
                cancelled: {
                    label: "Đã huỷ",
                    badgeClass: "bg-rose-50 text-rose-800 border-rose-200",
                    dotClass: "bg-rose-500",
                },
            };
            return (
                map[val] ?? {
                    label: val,
                    badgeClass: "bg-gray-50 text-gray-700 border-gray-200",
                    dotClass: "bg-gray-400",
                }
            );
        },

        pageList() {
            const total = this.meta.last_page || 1;
            const current = this.meta.current_page || 1;
            let start = Math.max(1, current - 2);
            let end = Math.min(total, start + 4);
            start = Math.max(1, end - 4);
            const pages = [];
            for (let p = start; p <= end; p++) pages.push(p);
            return pages;
        },

        formatPrice(value) {
            return new Intl.NumberFormat("vi-VN").format(value || 0);
        },

        formatDate(value) {
            if (!value) return "—";
            return new Date(value).toLocaleString("vi-VN", {
                hour: "2-digit",
                minute: "2-digit",
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
            });
        },

        showNotice(type, message) {
            this.notice = { type, message };
            setTimeout(() => {
                this.notice.message = "";
            }, 3500);
        },
    };
};
