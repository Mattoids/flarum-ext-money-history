import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import UserPage from 'flarum/forum/components/UserPage';
import LinkButton from 'flarum/common/components/LinkButton';
import MoneyHistoryPage from './components/MoneyHistoryPage';
import UserMoneyHistory from "./models/UserMoneyHistory";

app.initializers.add('flarum-ext-money-history', () => {
  app.store.models.userMoneyHistory = UserMoneyHistory;

  app.routes.userMoneyHistory = {
    path: '/u/:username/money/history',
    component: MoneyHistoryPage,
  };

  extend(UserPage.prototype, 'navItems', function (items) {
    if (app.session.user.id() !== this.user.id()) {
      if (!this.user || !this.user.attribute('canQueryOthersHistory')) {
        return;
      }
    }


    items.add('userMoneyHistory', LinkButton.component({
      href: app.route('userMoneyHistory', {
        username: this.user.slug(),
      }),
      icon: 'fas fa-money-bill',
    }, app.translator.trans('mattoid-money-history.forum.nav')));
  });
});
