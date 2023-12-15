import Model from 'flarum/common/Model';

export default class UserMoneyHistory extends Model {}
Object.assign(UserMoneyHistory.prototype, {
  type : Model.attribute('type'),
  money : Model.attribute('money'),
  sourceDesc : Model.attribute('source_desc'),
  changeTime : Model.attribute('change_time'),
  balanceMoney: Model.attribute('balance_money'),
  lastMoney: Model.attribute('last_money'),
  user : Model.hasOne('user'),
  createUser : Model.hasOne('createUser')
})
