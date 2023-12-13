import UserPage from 'flarum/forum/components/UserPage';
import MoneyHistoryList from "./MoneyHistoryList";

export default class MoneyHistoryPage extends UserPage {

    oninit(vnode) {
        super.oninit(vnode);

        this.loadUser(m.route.param('username'));
    }

    content() {
      return (
        <div className="MoneyHistoryPage">
          {MoneyHistoryList.component({
              params: {
                user: this.user,
              },
            })}
          </div>
      );
    }
}
