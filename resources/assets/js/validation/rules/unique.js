import { post } from '../../helpers/api'
import { message } from './function'

export default {
    messages: message('unique', ...window.Laravel.locale),
    validate(value, args) {
        let [database, column, idIgnore = ''] = args

        return axios.post('api/check-exist', { database, column, value, idIgnore })
            .then(res => {
                console.log(res.data.valid)
                return {
                    valid: !res.data.valid
                }
              })
              .catch(err => {
                  return {
                      valid: false,
                      data: { message: err.response.data.http_status.message }
                  }
              })
    }
};
