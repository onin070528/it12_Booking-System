import "./bootstrap";

import Alpine from "alpinejs";

import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
// FullCalendar CSS - using CDN or import from resources if needed
// import "@fullcalendar/core/main.css";
// import "@fullcalendar/daygrid/main.css";

window.Alpine = Alpine;
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.interactionPlugin = interactionPlugin;

Alpine.start();
