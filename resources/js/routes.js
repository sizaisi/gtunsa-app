import config from './config'

import TramitesPage from './pages/TramitesPage'
import EstadosPage from './pages/EstadosPage'

const routes = [    
    { path: '/', component: TramitesPage, name: 'inicio', props: { ruta: config.API_URL } },        
    { path: '/tramites', component: TramitesPage, name: 'tramites', props: true }, 
    { path: '/estados', component: EstadosPage, name: 'estados', props: true },
                 
]

export default routes