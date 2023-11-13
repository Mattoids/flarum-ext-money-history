import app from 'flarum/admin/app';

app.initializers.add('mattoid-money-history', () => {
  app.extensionData.for("mattoid-money-history")
    .registerSetting({
      setting: 'mattoid-money-history.request-type-get',
      help: app.translator.trans('mattoid-money-history.admin.settings.request-type-get-requirement'),
      label: app.translator.trans('mattoid-money-history.admin.settings.request-type-get'),
      type: 'switch',
      default: false
    })
    .registerSetting({
      setting: 'mattoid-money-history.request-type-post',
      help: app.translator.trans('mattoid-money-history.admin.settings.type-requirement'),
      label: app.translator.trans('mattoid-money-history.admin.settings.request-type-post'),
      type: 'switch',
      default: true
    })
    .registerSetting({
      setting: 'mattoid-money-history.request-type-put',
      help: app.translator.trans('mattoid-money-history.admin.settings.type-requirement'),
      label: app.translator.trans('mattoid-money-history.admin.settings.request-type-put'),
      type: 'switch',
      default: true
    })
    .registerSetting({
      setting: 'mattoid-money-history.request-type-delete',
      help: app.translator.trans('mattoid-money-history.admin.settings.type-requirement'),
      label: app.translator.trans('mattoid-money-history.admin.settings.request-type-delete'),
      type: 'switch',
      default: true
    })
});
