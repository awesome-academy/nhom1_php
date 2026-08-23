document.addEventListener("alpine:init", () => {
    Alpine.data("cartPage", (config = {}) => ({
        loading: true,
        items: [],
        itemCount: 0,
        total: 0,
        updating: {},
        removing: {},
        errorMessage: "",

        showCheckoutModal: false,
        submittingOrder: false,
        modalError: "",
        checkoutForm: {
            customer_name: config.user?.name || "",
            phone: config.user?.phone || "",
            address: config.user?.address || "",
            note: "",
        },

        routes: {
            cartData: config.routes?.cartData || "/cart/data",
            cartItems: config.routes?.cartItems || "/cart/items",
            checkout: config.routes?.checkout || "/checkout",
            orderRedirect: config.routes?.orderRedirect || "/orders?ordered=1",
        },

        init() {
            this.fetchCart();
        },

        getCsrfToken() {
            return (
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || ""
            );
        },

        applyCartData(data) {
            this.items = data.items ?? (Array.isArray(data) ? data : []);
            this.itemCount =
                data.item_count ??
                data.total_items ??
                this.items.reduce((sum, i) => sum + Number(i.quantity || 1), 0);
            this.total =
                data.total ??
                data.subtotal ??
                this.items.reduce((sum, i) => sum + this.getLineTotal(i), 0);
        },

        getItemName(item) {
            return (
                item.product_name ||
                (item.product && item.product.name) ||
                item.name ||
                ""
            );
        },

        getUnitPrice(item) {
            return Number(
                item.unit_price ??
                    item.price ??
                    (item.product && item.product.price) ??
                    0,
            );
        },

        getLineTotal(item) {
            if (item.line_total !== undefined && item.line_total !== null)
                return Number(item.line_total);
            if (item.subtotal !== undefined && item.subtotal !== null)
                return Number(item.subtotal);
            return this.getUnitPrice(item) * Number(item.quantity || 1);
        },

        async fetchCart() {
            this.loading = true;
            try {
                const res = await fetch(this.routes.cartData, {
                    headers: { Accept: "application/json" },
                });
                const json = await res.json();
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = "Không thể tải giỏ hàng.";
            } finally {
                this.loading = false;
            }
        },

        async updateQuantity(item, quantity) {
            const qty = Number(quantity);
            if (qty < 1) return;

            this.updating = { ...this.updating, [item.id]: true };
            this.errorMessage = "";

            try {
                const res = await fetch(`${this.routes.cartItems}/${item.id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.getCsrfToken(),
                    },
                    body: JSON.stringify({ quantity: qty }),
                });
                const json = await res.json();
                if (!res.ok)
                    throw new Error(
                        json.message ||
                            Object.values(json.errors ?? {}).flat()[0] ||
                            "Không thể cập nhật số lượng.",
                    );
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = e.message;
            } finally {
                const nextUpdating = { ...this.updating };
                delete nextUpdating[item.id];
                this.updating = nextUpdating;
            }
        },

        async removeItem(item) {
            this.removing = { ...this.removing, [item.id]: true };
            this.errorMessage = "";

            try {
                const res = await fetch(`${this.routes.cartItems}/${item.id}`, {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.getCsrfToken(),
                    },
                });
                const json = await res.json();
                if (!res.ok)
                    throw new Error(json.message || "Không thể xoá món.");
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = e.message;
            } finally {
                const nextRemoving = { ...this.removing };
                delete nextRemoving[item.id];
                this.removing = nextRemoving;
            }
        },

        openCheckoutModal() {
            if (this.items.length === 0) return;
            this.modalError = "";
            this.showCheckoutModal = true;
        },

        async submitOrder() {
            if (
                !this.checkoutForm.phone.trim() ||
                !this.checkoutForm.address.trim()
            ) {
                this.modalError =
                    "Vui lòng nhập đầy đủ Số điện thoại và Địa chỉ nhận hàng.";
                return;
            }

            this.submittingOrder = true;
            this.modalError = "";

            try {
                const res = await fetch(this.routes.checkout, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.getCsrfToken(),
                    },
                    body: JSON.stringify(this.checkoutForm),
                });
                const json = await res.json();
                if (!res.ok)
                    throw new Error(
                        json.message ||
                            Object.values(json.errors ?? {}).flat()[0] ||
                            "Đặt hàng không thành công.",
                    );

                window.location.href = this.routes.orderRedirect;
            } catch (e) {
                this.modalError = e.message;
                this.submittingOrder = false;
            }
        },

        formatPrice(value) {
            return new Intl.NumberFormat("vi-VN").format(Number(value) || 0);
        },
    }));
});
