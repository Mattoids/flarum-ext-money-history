import Component from "flarum/Component";
import Link from "flarum/components/Link";
import avatar from "flarum/helpers/avatar";
import username from "flarum/helpers/username";

export default class MoneyHistoryListItem extends Component {
  view() {
    const { historyEntry } = this.attrs;
    const createdAt = historyEntry.createdAt();
    const money = historyEntry.money();
    const sourceDesc = historyEntry.sourceDesc();
    const historyId = historyEntry.id();
    const actor = historyEntry.actor();
    const balanceBefore = historyEntry.balanceBefore();
    const balanceAfter = historyEntry.balanceAfter();
    const moneyType = app.translator.trans(historyEntry.type() === 'D' ? "mattoid-money-history.forum.record.money-out" : "mattoid-money-history.forum.record.money-in");
    const moneyTypeStyle = historyEntry.type() === 'D' ? "color:red" : "color:green";

    return (
      <div className="moneyHistoryContainer">
        <div style="padding-top: 5px;">
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-type')}: </b>
          <span style={moneyTypeStyle}>{moneyType}</span>&nbsp;|&nbsp;

          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-assign-at')}: </b>
          {createdAt}
        </div>

        <div style="padding-top: 5px;">
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-id')}: </b>
          {historyId}&nbsp;|&nbsp;
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-from-user')}: </b>
          <Link href="#" className="moneyHistoryUser" style="color:var(--heading-color)">
            {avatar(actor)} {username(actor)}
          </Link>&nbsp;|&nbsp;
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-amount')}: </b>
          {money}&nbsp;|&nbsp;
          <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-balance')}: </b>
          {balanceBefore}&nbsp;→&nbsp;{balanceAfter}&nbsp;|&nbsp;
          <span>
            <b>{app.translator.trans('mattoid-money-history.forum.record.money-list-transfer-notes')}: </b>
            {sourceDesc}
          </span>
        </div>
      </div>
    );
  }
}
