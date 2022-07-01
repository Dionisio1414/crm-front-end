import {appLocalStorage} from "@/services";
import { PROTOCOL } from '@/domain';

export default function formationUrl() {
    const domain = appLocalStorage.getItem('domain');
    // console.log('domain', domain)

    return `${PROTOCOL}://${domain}/api/v1/`;
}
