import Component from "flarum/Component";
import Link from "flarum/components/Link";
import avatar from "flarum/helpers/avatar";
import username from "flarum/helpers/username";

export default class TransferHistoryListItem extends Component {
  view() {
    const {userMoneyHistory} = this.attrs;
    const changeTime = userMoneyHistory.changeTime();
    const money = userMoneyHistory.money();
    const sourceDesc = userMoneyHistory.sourceDesc();
    const moneyID = userMoneyHistory.id();
    const moneyUser = userMoneyHistory.user();
    const createUser = userMoneyHistory.createUser();
    const moneyType = app.translator.trans(userMoneyHistory.type()==='D'?"mattoid-money-history.forum.record.money-out":"mattoid-money-history.forum.record.money-in");
    const moneyTypeStyle = userMoneyHistory.type()==='D'?"color:red":"color:green";

    return (
      <div className="transferHistoryContainer">
        <div style="padding-top: 5px;">
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-type')}: </b>
          <span style={moneyTypeStyle}>{moneyType}</span>&nbsp;|&nbsp;

          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-assign-at')}: </b>
          {changeTime}
        </div>

        <div style="padding-top: 5px;">
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-id')}: </b>
          {moneyID}&nbsp;|&nbsp;
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-from-user')}: </b>
          <Link href="#" className="moneyHistoryUser" style="color:var(--heading-color)">
            {avatar(createUser)} {username(createUser)}
          </Link>&nbsp;|&nbsp;

          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-target-user')}: </b>
          <Link href="#" className="moneyHistoryUser" style="color:var(--heading-color)">
            {avatar(moneyUser)} {username(moneyUser)}
          </Link>&nbsp;|&nbsp;
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-amount')}: </b>
          {money}

          <span>&nbsp;|&nbsp;
            <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-transfer-notes')}: </b>
            {sourceDesc}
          </span>
        </div>
      </div>
    );
  }
}
