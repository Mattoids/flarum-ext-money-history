import Component from "flarum/Component";
import Link from "flarum/components/Link";
import avatar from "flarum/helpers/avatar";
import username from "flarum/helpers/username";

export default class TransferHistoryListItem extends Component {
  view() {
    const {moneyHistory} = this.attrs;
    const changeTime = moneyHistory.change_time;
    const money = moneyHistory.attributes.money;
    const sourceDesc = moneyHistory.attributes.source_desc;
    const moneyID = moneyHistory.attributes.id;
    const moneyUser = app.session.user;
    const createUser = '';
    const moneyType = app.translator.trans(moneyHistory.attributes.type==='C'?"mattoid-money-history.forum.record.money-out":"mattoid-money-history.forum.record.money-in");
    const moneyTypeStyle = moneyHistory.attributes.type==='C'?"color:red":"color:green";

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

          <span>|&nbsp;
            <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-transfer-notes')}: </b>
            {sourceDesc}
          </span>
        </div>
      </div>
    );
  }
}
