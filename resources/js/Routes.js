// Basic
import Home from "./pages/Home";

// Guest
import Login from "./pages/Login";
import Register from "./pages/Register";

// Auth
import Dashboard from "./pages/Dashboard/";
/// Dashboaard Server
import DashboardServer from "./pages/Dashboard/Server";
import DashboardServerAdd from "./pages/Dashboard/Server/DashboardServerAdd";
import DashboardServerCurrent from "./pages/Dashboard/Server/DashboardServerCurrent";
/// Dashboard API
import DashboardApi from "./pages/Dashboard/Api";
import DashboardApiAdd from "./pages/Dashboard/Api/DashboardApiAdd";
import DashboardUsers from "./pages/Dashboard/Users";
import DashboardUsersAdd from "./pages/Dashboard/Users/DashboardUsersAdd";

const BasicRoutes = [{ name: "home", path: "/", exact: true, Component: Home }];
const GuestRoutes = [
    { name: "login", path: "/login", exact: true, Component: Login },
    { name: "register", path: "/register", exact: true, Component: Register },
];
const AuthRoutes = [
    { name: "home", path: "/dashboard", exact: true, Component: Dashboard },
    {
        name: "home",
        path: "/dashboard/server",
        exact: true,
        Component: DashboardServer,
    },
    {
        name: "home",
        path: "/dashboard/server/add",
        exact: true,
        Component: DashboardServerAdd,
    },
    {
        name: "home",
        path: "/dashboard/server/:id",
        exact: true,
        Component: DashboardServerCurrent,
    },
    {
        name: "home",
        path: "/dashboard/api",
        exact: true,
        Component: DashboardApi,
    },
    {
        name: "home",
        path: "/dashboard/api/add",
        exact: true,
        Component: DashboardApiAdd,
    },
    {
        name: "home",
        path: "/dashboard/users",
        exact: true,
        Component: DashboardUsers,
    },
    {
        name: "home",
        path: "/dashboard/users/add",
        exact: true,
        Component: DashboardUsersAdd,
    },
];
export { BasicRoutes, GuestRoutes, AuthRoutes };
