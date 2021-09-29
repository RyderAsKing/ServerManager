const ExchangeToken = (email, password) => {
    return axios
        .post(`/api/user/login`, { email: email, password: password })
        .then((response) => {
            return response.data;
        });
};

const ListSubUsers = (pageNumber = 1) => {
    return axios.get(`/api/user/subusers`).then((response) => {
        return response.data;
    });
};

const ListServersFromApi = (api_id) => {
    return axios.get(`/api/api/${api_id}/servers`).then((response) => {
        return response.data;
    });
};

const ListServers = (pageNumber = 1) => {
    return axios.get(`/api/server/?page=${pageNumber}`).then((response) => {
        return response.data;
    });
};

const ListAllServers = () => {
    return axios.get(`/api/server/all`).then((response) => {
        return response.data;
    });
};

const DestroyServer = (server_id) => {
    return axios.get(`/api/server/${server_id}/destroy`).then((response) => {
        return response.data;
    });
};

const AddServer = (server_id, api_id) => {
    return axios
        .post(`/api/server/add`, { server_id: server_id, api_id: api_id })
        .then((response) => {
            return response.data;
        });
};

const PowerActions = (db_id, action) => {
    if (
        action != "start" &&
        action != "stop" &&
        action != "restart" &&
        action != "kill"
    ) {
        return {
            error: true,
            error_message: "Invalid method",
            action_passed: action,
        };
    } else {
        return axios
            .post(`/api/server/${db_id}/power`, {
                action: action,
            })
            .then((response) => {
                return response.data;
            });
    }
};

const GetServerInformation = (db_id) => {
    return axios.get(`/api/server/${db_id}`).then((response) => {
        return response.data;
    });
};

const ListApis = (pageNumber = 1) => {
    return axios.get(`/api/api/?page=${pageNumber}`).then((response) => {
        return response.data;
    });
};

const ListAllApis = () => {
    return axios.get(`/api/api/all`).then((response) => {
        return response.data;
    });
};

const DestroyApi = (db_id) => {
    return axios.get(`/api/api/${db_id}/destroy`).then((response) => {
        return response.data;
    });
};

const CreateApi = (type, api, api_pass, name, hostname, protocol) => {
    return axios
        .post(`/api/api/add`, {
            type: type,
            api: api,
            api_pass: api_pass,
            name: name,
            hostname: hostname,
            protocol: protocol,
        })
        .then((response) => {
            return response.data;
        });
};

export {
    ExchangeToken,
    ListSubUsers,
    ListServersFromApi,
    ListServers,
    ListAllServers,
    DestroyServer,
    AddServer,
    PowerActions,
    GetServerInformation,
    ListApis,
    ListAllApis,
    CreateApi,
    DestroyApi,
};
