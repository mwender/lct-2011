/*
 * CrossSlide jQuery plugin v0.6
 *
 * Copyright 2007-2010 by Tobia Conforto <tobia.conforto@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */
(function(){var d=jQuery,a=(d.fn.startAnimation?"startAnimation":"animate"),c="pause plugin missing.";function e(h){for(var g=1;g<arguments.length;g++){h=h.replace(new RegExp("\\{"+(g-1)+"}","g"),arguments[g])}return h}function f(){arguments[0]="CrossSlide: "+arguments[0];throw new Error(e.apply(null,arguments))}function b(i){var g=1;var h=i.replace(/^\s*|\s*$/g,"").split(/\s+/);if(h.length>3){throw new Error()}if(h[0]=="center"){if(h.length==1){h=["center","center"]}else{if(h.length==2&&h[1].match(/^[\d.]+x$/i)){h=["center","center",h[1]]}}}if(h.length==3){g=parseFloat(h[2].match(/^([\d.]+)x$/i)[1])}var j=h[0]+" "+h[1];if(j=="left top"||j=="top left"){return{xrel:0,yrel:0,zoom:g}}if(j=="left center"||j=="center left"){return{xrel:0,yrel:0.5,zoom:g}}if(j=="left bottom"||j=="bottom left"){return{xrel:0,yrel:1,zoom:g}}if(j=="center top"||j=="top center"){return{xrel:0.5,yrel:0,zoom:g}}if(j=="center center"){return{xrel:0.5,yrel:0.5,zoom:g}}if(j=="center bottom"||j=="bottom center"){return{xrel:0.5,yrel:1,zoom:g}}if(j=="right top"||j=="top right"){return{xrel:1,yrel:0,zoom:g}}if(j=="right center"||j=="center right"){return{xrel:1,yrel:0.5,zoom:g}}if(j=="right bottom"||j=="bottom right"){return{xrel:1,yrel:1,zoom:g}}return{xrel:parseInt(h[0].match(/^(\d+)%$/)[1])/100,yrel:parseInt(h[1].match(/^(\d+)%$/)[1])/100,zoom:g}}d.fn.crossSlide=function(i,k,l){var g=this,j=this.width(),h=this.height();if(g.length!=1){f("crossSlide() must be called on exactly 1 element")}g.get(0).crossSlideArgs=[i,k,l];k=d.map(k,function(m){return d.extend({},m)});if(!i.easing){i.easing=i.variant?"swing":"linear"}if(!l){l=function(){}}(function(o){var m=0;function n(q,p){p.onload=function(r){m++;k[q].width=p.width;k[q].height=p.height;if(m==k.length){o()}};p.src=k[q].src;if(q+1<k.length){n(q+1,new Image())}}n(0,new Image())})(function(){if(!i.fade){f("missing fade parameter.")}if(i.speed&&i.sleep){f("you cannot set both speed and sleep at the same time.")}var A=Math.round(i.fade*1000);if(i.sleep){var z=Math.round(i.sleep*1000)}if(i.speed){var o=i.speed/1000,v=Math.round(A*o)}g.empty().css({overflow:"hidden",padding:0});if(!/^(absolute|relative|fixed)$/.test(g.css("position"))){g.css({position:"relative"})}if(!g.width()||!g.height()){f("container element does not have its own width and height")}if(i.shuffle){k.sort(function(){return Math.random()-0.5})}for(var t=0;t<k.length;++t){var m=k[t];if(!m.src){f("missing src parameter in picture {0}.",t+1)}if(o){switch(m.dir){case"up":m.from={xrel:0.5,yrel:0,zoom:1};m.to={xrel:0.5,yrel:1,zoom:1};var x=m.height-h-2*v;break;case"down":m.from={xrel:0.5,yrel:1,zoom:1};m.to={xrel:0.5,yrel:0,zoom:1};var x=m.height-h-2*v;break;case"left":m.from={xrel:0,yrel:0.5,zoom:1};m.to={xrel:1,yrel:0.5,zoom:1};var x=m.width-j-2*v;break;case"right":m.from={xrel:1,yrel:0.5,zoom:1};m.to={xrel:0,yrel:0.5,zoom:1};var x=m.width-j-2*v;break;default:f("missing or malformed dir parameter in picture {0}.",t+1)}if(x<=0){f("impossible animation: either picture {0} is too small or div is too large or fade duration too long.",t+1)}m.time_ms=Math.round(x/o)}else{if(!z){if(!m.from||!m.to||!m.time){f("missing either speed/sleep option, or from/to/time params in picture {0}.",t+1)}try{m.from=b(m.from)}catch(w){f('malformed "from" parameter in picture {0}.',t+1)}try{m.to=b(m.to)}catch(w){f('malformed "to" parameter in picture {0}.',t+1)}if(!m.time){f('missing "time" parameter in picture {0}.',t+1)}m.time_ms=Math.round(m.time*1000)}}if(m.from){d.each([m.from,m.to],function(p,C){C.width=Math.round(m.width*C.zoom);C.height=Math.round(m.height*C.zoom);C.left=Math.round((j-C.width)*C.xrel);C.top=Math.round((h-C.height)*C.yrel)})}var s,y;y=s=d(e('<img src="{0}"/>',m.src));if(m.href){y=d(e('<a href="{0}"></a>',m.href)).append(s)}if(m.onclick){y.click(m.onclick)}if(m.alt){s.attr("alt",m.alt)}if(m.href&&m.target){y.attr("target",m.target)}y.appendTo(g)}delete o;function n(D,C){var E=[0,A/(D.time_ms+2*A),1-A/(D.time_ms+2*A),1][C];return{left:Math.round(D.from.left+E*(D.to.left-D.from.left)),top:Math.round(D.from.top+E*(D.to.top-D.from.top)),width:Math.round(D.from.width+E*(D.to.width-D.from.width)),height:Math.round(D.from.height+E*(D.to.height-D.from.height))}}var u=g.find("img").css({position:"absolute",visibility:"hidden",top:0,left:0,border:0});u.eq(0).css({visibility:"visible"});if(!z){u.eq(0).css(n(k[0],i.variant?0:1))}var B=i.loop;function q(O,p){if(O%2==0){if(z){var E=O/2,S=(E-1+k.length)%k.length,P=u.eq(E),M=u.eq(S);var L=function(){l(E,P.get(0));M.css("visibility","hidden");setTimeout(p,z)}}else{var H=O/2,S=(H-1+k.length)%k.length,R=u.eq(H),M=u.eq(S),F=k[H].time_ms,N=n(k[H],i.variant?3:2);var L=function(){l(H,R.get(0));M.css("visibility","hidden");R[a](N,F,i.easing,p)}}}else{var D=Math.floor(O/2),G=Math.ceil(O/2)%k.length,Q=u.eq(D),C=u.eq(G),T={},K={visibility:"visible"},J={};if(G>D){K.opacity=0;J.opacity=1;if(i.doubleFade){T.opacity=0}}else{T.opacity=0;if(i.doubleFade){K.opacity=0;J.opacity=1}}if(!z){d.extend(K,n(k[G],0));if(!i.variant){d.extend(T,n(k[D],3));d.extend(J,n(k[G],1))}}if(d.isEmptyObject(J)){var L=function(){l(G,C.get(0),D,Q.get(0));C.css(K);Q[a](T,A,"linear",p)}}else{if(d.isEmptyObject(T)){var L=function(){l(G,C.get(0),D,Q.get(0));C.css(K);C[a](J,A,"linear",p)}}else{var L=function(){l(G,C.get(0),D,Q.get(0));C.css(K);C[a](J,A,"linear");Q[a](T,A,"linear",p)}}}}if(i.loop&&O==k.length*2-2){var I=L;L=function(){if(--B){I()}}}if(O>0){return q(O-1,L)}else{return L}}var r=q(k.length*2-1,function(){return r()});r()});return g};d.fn.crossSlideFreeze=function(){this.find("img").stop()};d.fn.crossSlideStop=function(){this.find("img").stop().remove()};d.fn.crossSlideRestart=function(){this.find("img").stop().remove();d.fn.crossSlide.apply(this,this.get(0).crossSlideArgs)};d.fn.crossSlidePause=function(){if(!d.fn.pause){f(c)}this.find("img").pause()};d.fn.crossSlideResume=function(){if(!d.fn.pause){f(c)}this.find("img").resume()}})();