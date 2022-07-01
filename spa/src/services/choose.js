import {httpClient} from "@/services";


export const  fetchItems= (url) => {
    return  httpClient.get(url)
}

export const selectItems = (url, data) => {
    return httpClient.post(url, {data})
}