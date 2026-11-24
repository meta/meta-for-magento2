/**
 * Copyright (c) Meta Platforms, Inc. and affiliates.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

define([
  'jquery',
  'Meta_Conversion/js/metaPixelTracker',
  'Magento_Customer/js/customer-data',
  'Magento_Checkout/js/model/payment/place-order-hooks'
], function ($, metaPixelTracker, customerData, placeOrderHooks) {
  'use strict';

  /*eslint one-var: ["error", "never"]*/
  return function (metaPixelData) {

    let payload = customerData.get('cart')().meta_payload;
    let currency = metaPixelData.browserEventData.payload.currency;
    let eventName = metaPixelData.payload.eventName;
    let singlePayment = false;

    metaPixelData = { ...metaPixelData, payload: payload};
    metaPixelData.browserEventData = { ...metaPixelData.browserEventData, payload: payload};
    metaPixelData.payload = { ...metaPixelData.payload, content_type: 'product'};

    metaPixelData.payload = {...metaPixelData.payload, currency: currency};
    metaPixelData.browserEventData.payload = { ...metaPixelData.browserEventData.payload, currency: currency};

    metaPixelData.payload = {...metaPixelData.payload, eventName: eventName};
    metaPixelData.browserEventData.payload = {...metaPixelData.browserEventData.payload, content_type: 'product'};

    if (payload !== null) {
      // Triggers when any payment method selects
      placeOrderHooks.afterRequestListeners.push(function () {
        metaPixelData.payload.eventId = null;
        // For multi payment active methods
        if ($('input[name=\'payment[method]\']').length > 1) {
          metaPixelTracker(metaPixelData);
        }

        // For single payment active methods
        if ($('input[name=\'payment[method]\']').length === 1 && !singlePayment) {
          metaPixelTracker(metaPixelData);
          singlePayment = true;
        }
      });

      // Triggers when billing-address added
      $(document).on('ajaxComplete', function (event, xhr, settings) {
        if (settings.url.indexOf('/billing-address') !== -1 && settings.type === 'POST') {
          metaPixelTracker(metaPixelData);
        }
      });
    }
  };
});
