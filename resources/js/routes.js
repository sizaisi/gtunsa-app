import Tramites from "./pages/Tramites";
import NuevoTramite from "./pages/NuevoTramite";
import BachillerAutomatico from "./pages/Bachiller-Automatico/Procedimientos";
import TituloTesis from "./pages/Titulo-Tesis/Procedimientos";

const routes = [
    { path: "/", component: Tramites, name: "tramites" },
    { path: "/nuevo_tramite", component: NuevoTramite, name: "nuevo-tramite", props: true },    
    { path: "/bachiller-automatico", component: BachillerAutomatico, name: "Bachiller-Automatico", props: true },
    { path: "/titulo-tesis", component: TituloTesis, name: "Titulo-Tesis", props: true }
];

export default routes;
