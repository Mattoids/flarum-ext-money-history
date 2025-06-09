import Component from "flarum/Component";
import app from "flarum/app";
import LoadingIndicator from "flarum/components/LoadingIndicator";
import Button from "flarum/components/Button";

import MoneyHistoryListItem from "./MoneyHistoryListItem";

export default class TransferHistoryList extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    this.loading = true;
    this.moreResults = false;
    this.userMoneyHistory = [];
    this.user = this.attrs.params.user;
    this.loadResults();
  }

  view() {
    let loading;

    if (this.loading) {
      loading = LoadingIndicator.component({ size: "large" });
    }

    return (
      <div>
        <div style="padding-bottom:10px; font-size: 24px;font-weight: bold;">
          {app.translator.trans("mattoid-money-history.forum.title")}
        </div>
        <ul style="margin: 0;padding: 0;list-style-type: none;position: relative;">
          {this.userMoneyHistory.map((userMoneyHistory) => {
            return (
              <li style="padding-top:5px" key={userMoneyHistory.id} data-id={userMoneyHistory.id}>
                {MoneyHistoryListItem.component({ userMoneyHistory })}
              </li>
            );
          })}
        </ul>

        {!this.loading && this.userMoneyHistory.length===0 && (
          <div>
            <div style="font-size:1.4em;color: var(--muted-more-color);text-align: center;height: 300px;line-height: 100px;">{app.translator.trans("mattoid-money-history.forum.list-empty")}</div>
          </div>
        )}

        {this.hasMoreResults() && (
          <div style="text-align:center;padding:20px">
            <Button className={'Button Button--primary'} disabled={this.loading} loading={this.loading} onclick={() => this.loadMore()}>
              {app.translator.trans('mattoid-money-history.forum.money-list-load-more')}
            </Button>
          </div>
        )}

        {this.loading && (
          <div class="DiscussionList">
            <div class="DiscussionList-loadMore">
              <div aria-label="loading…" role="status" data-size="medium"
                   class="LoadingIndicator-container LoadingIndicator-container--block LoadingIndicator-container--medium">
                <div aria-hidden="true" class="LoadingIndicator"></div>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  }

  loadMore() {
    this.loading = true;
    this.loadResults(this.userMoneyHistory.length);
  }

  parseResults(results) {
    this.moreResults = !!results.payload.links && !!results.payload.links.next;
    [].push.apply(this.userMoneyHistory, results);
    this.loading = false;
    m.redraw();

    return results;
  }

  hasMoreResults() {
    return this.moreResults;
  }

  loadResults(offset = 0) {
    this.loading = true;
    let url = '/users/' + this.user.id() + '/money/history';
    return app.store
      .find(url, {
        filter: {
          user: this.user.id(),
        },
        page: {
          offset,
        },
      })
      .catch(() => {})
      .then(this.parseResults.bind(this));
  }
}
