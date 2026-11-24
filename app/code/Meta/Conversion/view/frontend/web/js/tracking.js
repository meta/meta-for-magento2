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

define([],
  function () {
    'use strict';
    return {
      /*eslint max-depth: ["error", 3]*/
      /*eslint one-var: ["error", "never"]*/
      getCookie : function (name) {
        var cookie = ' ' + document.cookie;
        var search = ' ' + name + '=';
        var setStr = null;
        var offset = 0;
        var end = 0;

        if (cookie.length > 0) {
          offset = cookie.indexOf(search);
          if (offset !== -1) {
            offset += search.length;
            end = cookie.indexOf(';', offset);
            if (end === -1) {
              end = cookie.length;
            }
            setStr = decodeURI(cookie.substring(offset, end));
          }
        }
        return setStr;
      },

      /*eslint one-var: ["error", "never"]*/
      delCookie : function (name) {
        var date = new Date(0);
        var cookie = name + '=' + '; path=/; expires=' + date.toUTCString();

        document.cookie = cookie;
      },

      parseJson : function (json) {
        json = decodeURIComponent(json.replace(/\+/g, ' '));
        return JSON.parse(json);
      }
    };
});
