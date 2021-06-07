import Tramites from "./pages/Tramites";
import NuevoTramite from "./pages/NuevoTramite";
import Estados from "./pages/Estados";

const routes = [
    { path: "/", component: Tramites, name: "tramites" },
    { path: "/nuevo_tramite", component: NuevoTramite, name: "nuevo-tramite", props: true },
    { path: "/estados", component: Estados, name: "estados", props: true }
];

export default routes;
