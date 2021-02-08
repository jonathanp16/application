import moment from "moment";

export default {
  install(Vue, options) {

    Vue.prototype.$calendar = function (timestamp) {
      return moment(timestamp).local().format('LLL');
    }

    Vue.prototype.$fromNow = function (timestamp) {
      return moment(timestamp).local().fromNow();
    }
  }
}
