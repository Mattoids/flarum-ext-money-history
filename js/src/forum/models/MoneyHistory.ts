import Model from 'flarum/common/Model';

export default class MoneyHistory extends Model {
  type = Model.attribute('type')
  money = Model.attribute('money')
  source_desc = Model.attribute('sourceDesc')
  change_time = Model.attribute('changeTime', Model.transformDate)
}
