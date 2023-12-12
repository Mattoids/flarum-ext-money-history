import app from 'flarum/forum/app';
import Component, {ComponentAttrs} from 'flarum/common/Component';

interface MoneyFormatAttrs extends ComponentAttrs {
    money: number
}

export default class FormattedMoney extends Component<MoneyFormatAttrs> {
    view() {
        const moneyName = app.forum.attribute<string>('antoinefr-money.moneyname') || '[money]';

        return m('span', moneyName.replace('[money]', this.attrs.money + ''));
    }
}
