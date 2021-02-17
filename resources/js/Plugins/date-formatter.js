import moment from "moment";

export function calendar(timestamp){
  return moment(timestamp).local().format('LLL');
}

export function fromNow(timestamp){
  return moment(timestamp).local().fromNow();
}

export default {
  install(Vue, options) {

    Vue.prototype.$calendar = calendar;

    Vue.prototype.$fromNow = fromNow;
  }
}
