import httpClient from "./http-client/http-client";

export const getProducts = (url) => {
    return  httpClient.get(url)
}