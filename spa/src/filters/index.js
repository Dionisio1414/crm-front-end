import moment from 'moment'
import Vue from 'vue'


const filters = () => {

    Vue.filter('dateForDatePicker', ({value, format}) => {
        if (!value) return ''
        return moment(value).format(format)
    })
    Vue.filter('formatDate', (value) => {
        if (!value) return ''
        return moment(value).format('DD.MM.YYYY')
    })
    Vue.filter('formatDateSecond', (value) => {
        if (!value) return ''
        let date = value.split(' ')
        // let newValue = moment(date[0]).format('DD.MM.YYYY')
        // return newValue.split('/').join('.')
        return date[0].split('-').join('.')
    })

    Vue.filter("capitalize", (value) => {
        if (!value) return ''
        value = value.toString()
        return value.split('.').reverse().join('-');
    })

    Vue.filter("formatPrice", (value) => {
        if (!value) return '0'
        value = value.toString();

        return new Intl.NumberFormat({ minimumFractionDigits: 2 }).format(value);
    })

    Vue.filter("formatIsNaN", (value) => {
        if (!value) return '0'
        value = value.toString()

        return Number.isNaN(value) ? '0' : value;
    })
}

export default filters