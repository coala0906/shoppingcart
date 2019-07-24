import axios from 'axios'
export default {
    getMember: function (params) {
        return axios.get('member')
    },
}
