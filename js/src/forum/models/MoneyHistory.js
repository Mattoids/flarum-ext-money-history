import Model from 'flarum/common/Model';

export default class MoneyHistory extends Model {}
Object.assign(MoneyHistory.prototype, {
  type : Model.attribute('type'),
  money : Model.attribute('money'),
  sourceDesc : Model.attribute('source_desc'),
  changeTime : Model.attribute('change_time', Model.transformDate),
  user : Model.hasOne('user'),
  createUser : Model.hasOne('createUser')
})
