import app from 'flarum/forum/app';
import UserPage from 'flarum/forum/components/UserPage';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import extractText from 'flarum/common/utils/extractText';
import { ApiPayloadPlural } from 'flarum/common/Store';
import MoneyHistoryRecord from './MoneyHistoryRecord';
import MoneyHistory from "../models/MoneyHistory";

export default class MoneyHistoryPage extends UserPage {
    loading: boolean = true
    money: MoneyHistory[] = []

    oninit(vnode: any) {
        super.oninit(vnode);

        this.loadUser(m.route.param('username'));
    }

    show(user: any) {
        super.show(user);

        app.setTitle(extractText(app.translator.trans('wanecho-money-tip.forum.profile.title')));

        this.loadRewards();
    }

    loadRewards() {
        app.request<ApiPayloadPlural>({
            method: 'GET',
            url: app.forum.attribute('apiUrl') + '/users/' + this.user!.id() + '/money/history',
        }).then(payload => {
            this.money = app.store.pushPayload<MoneyHistory[]>(payload);
            this.loading = false;
            m.redraw();
        });
    }

    content() {
        if (this.loading) {
            return LoadingIndicator.component();
        }

        return m('ul.MoneyHistoryPage.MoneyHistoryRecords', [
            this.money.map(reward => MoneyHistoryRecord.component({ reward, showReceiver: true })),
        ]);
    }
}
