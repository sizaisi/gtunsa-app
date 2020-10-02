import config from "./config";

import Inicio from "./pages/Inicio";
import NuevoTramite from "./pages/NuevoTramite";
import Estados from "./pages/Estados";

const routes = [
    {
        path: "/",
        component: Inicio,
        name: "inicio"
    },
    {
        path: "/nuevo_tramite",
        component: NuevoTramite,
        name: "nuevo-tramite"
    },
    { path: "/estados", component: Estados, name: "estados", props: true }
];

export default routes;
