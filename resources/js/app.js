import "./bootstrap";

import Alpine from "alpinejs";

import.meta.glob(["../images/**"]);

import "./admin-order-manager";
import "./cart";

window.Alpine = Alpine;

Alpine.start();
