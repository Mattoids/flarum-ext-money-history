import app from 'flarum/forum/app';
import Component, { ComponentAttrs } from 'flarum/common/Component';
import Link from 'flarum/common/components/Link';
import avatar from 'flarum/common/helpers/avatar';
import humanTime from 'flarum/common/helpers/humanTime';
import icon from 'flarum/common/helpers/icon';
import username from 'flarum/common/helpers/username';
import MoneyHistory from '../models/MoneyHistory';
import FormattedMoney from './FormattedMoney';

interface RewardRecordAttrs extends ComponentAttrs {
    money: MoneyHistory
    showReceiver?: boolean
}

export default class MoneyHistoryRecord extends Component<RewardRecordAttrs> {
    view() {
        const { money, showReceiver } = this.attrs;

        const giverContent = [
        ];

        const receiverContent = [
        ];

        return m('li.MoneyRewardRecord', [
            m('span.MoneyRewardRecordIcon', icon('fas fa-gift')),


            // m('span.MoneyRewardRecordGiver', [
            //     ' ',
            //     app.translator.trans('wanecho-money-tip.forum.record.from'),
            //     ' ',
            //     giver ? Link.component({
            //         className: 'MoneyRewardRecordUser',
            //         href: app.route.user(giver),
            //     }, giverContent) : m('span.MoneyRewardRecordUser', giverContent),
            // ]),
            // m('span.MoneyRewardRecordAmount', FormattedMoney.component({
            //     money: reward.amount(),
            // })),
            // m('span.MoneyRewardRecordDate', humanTime(reward.createdAt()!)),
            // showReceiver ? m('span.MoneyRewardRecordReceiver', [
            //     ' ',
            //     app.translator.trans('wanecho-money-tip.forum.record.to'),
            //     ' ',
            //     receiver ? Link.component({
            //         className: 'MoneyRewardRecordUser',
            //         href: app.route.user(receiver),
            //     }, receiverContent) : m('span.MoneyRewardRecordUser', receiverContent),
            // ]) : null,
            // showReceiver && post ? m('span.MoneyRewardRecordPost', [
            //     ' ',
            //     app.translator.trans('wanecho-money-tip.forum.record.post', {
            //         number: post.number(),
            //         title: post.discussion()?.title() || 'N/A',
            //         a: Link.component({
            //             href: app.route.post(post),
            //         }),
            //     }),
            // ]) : null,
        ]);
    }
}
