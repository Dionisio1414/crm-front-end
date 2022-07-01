import Echo from 'laravel-echo'
import { WEBSOCKET_DOMAIN } from "@/domain"
import { appLocalStorage } from "@/services"
import getAccessToken from '@/helper/getAccessToken'

export default class WsService {
    constructor() {
        this.echo = new Echo({
            broadcaster: 'socket.io',
            authEndpoint: 'api/broadcasting/auth',
            host: `${WEBSOCKET_DOMAIN}`,
            transports: ['websocket'],
            auth: {
              headers: {
                Authorization: `Bearer ${getAccessToken()}`
              }
            }
        })
        this.userId = appLocalStorage.getItem('user') ? appLocalStorage.getItem('user').id : {}
        this.onListen = this.onListen.bind(this)
        this.sayHi = this.sayHi.bind(this)
    }

    onListen(type) {
        console.log(this.echo)
        return new Promise(resolve => {
            this.echo.private(`user.${this.userId}`).listen(type, e => {
                console.log(e)
                resolve(e)
            })
        })
    }

    sayHi() { alert('Hi!') }

}
