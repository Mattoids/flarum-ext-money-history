import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import UserPage from 'flarum/forum/components/UserPage';
import LinkButton from 'flarum/common/components/LinkButton';
import MoneyHistoryPage from './components/MoneyHistoryPage';
import MoneyHistory from "./models/MoneyHistory";

app.initializers.add('flarum-ext-money-history', () => {
  app.store.models.moneyHistory = MoneyHistory;

  app.routes.userMoneyHistory = {
    path: '/u/:username/money/history',
    component: MoneyHistoryPage,
  };

  extend(UserPage.prototype, 'navItems', function (items) {
    if (!app.session.user) {
      return;
    }


    items.add('moneyHistory', LinkButton.component({
      href: app.route('userMoneyHistory', {
        username: app.session.user.username(),
      }),
      icon: 'fas fa-money-bill',
    }, app.translator.trans('mattoid-money-history.forum.nav')));
  });
});
