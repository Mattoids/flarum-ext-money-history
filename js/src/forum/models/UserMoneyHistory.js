import Model from 'flarum/common/Model';

export default class UserMoneyHistory extends Model {}
Object.assign(UserMoneyHistory.prototype, {
  type : Model.attribute('type'),
  money : Model.attribute('money'),
  sourceDesc : Model.attribute('source_desc'),
  createdAt : Model.attribute('created_at'),
  balanceBefore: Model.attribute('balance_before'),
  balanceAfter: Model.attribute('balance_after'),
  user : Model.hasOne('user'),
  actor : Model.hasOne('actor')
})
