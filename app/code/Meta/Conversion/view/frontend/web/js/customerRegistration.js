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
    'Meta_Conversion/js/tracking',
    'Meta_Conversion/js/metaPixelTracker'
], function (cookies, metaPixelTracker) {
  'use strict';

  return function (metaPixelData) {
    let payload = null,
      eventName = '',
      currency = '',
      cookieName = 'event_customer_register';

    eventName = metaPixelData.payload.eventName;
    currency = metaPixelData.browserEventData.payload.currency;

    function isPayloadAvailable()
    {
      payload = cookies.getCookie(cookieName);

      if (payload !== null) {
        payload = cookies.parseJson(payload);
        return true;
      }
      return false;
    }

    function prepareServerPayload()
    {
      //eventName vanish here. So again need to reassign eventName to server payload
      metaPixelData.payload = {...metaPixelData.payload, ...metaPixelData.browserEventData.payload};
      metaPixelData.payload.eventName = eventName;
      metaPixelData.payload.currency = currency;
    }

    function prepareBrowserPayload()
    {
      metaPixelData.browserEventData.payload = payload;
      metaPixelData.browserEventData.payload.currency = currency;
    }

    if (isPayloadAvailable()) {
      //Prepare Browser Payload
      prepareBrowserPayload();

      //Prepare Server Payload
      prepareServerPayload();

      //Call to metaTracker
      metaPixelTracker(metaPixelData);
      cookies.delCookie(cookieName);
    }
  };
});
