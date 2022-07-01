import {appLocalStorage} from "@/services";

const getAccessToken = () => {
    let token = null;
    try {
        token = appLocalStorage.getItem('token');
    } catch (err) {
        console.error(err);
    }
    return token;
};

export default getAccessToken;
