import Model from 'flarum/common/Model';

export default class UserMoneyHistory extends Model { }
Object.assign(UserMoneyHistory.prototype, {
  balanceDelta: Model.attribute('balance_delta'),
  source: Model.attribute('source'),
  sourceKey: Model.attribute('source_key'),
  sourceParams: Model.attribute('source_params'),
  createdAt: Model.attribute('created_at'),
  balanceBefore: Model.attribute('balance_before'),
  balanceAfter: Model.attribute('balance_after'),
  user: Model.hasOne('user'),
  actor: Model.hasOne('actor')
})
