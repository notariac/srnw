var _cmNodeProperties={
		prefix:"",
		mainFolderLeft:"",
		mainFolderRight:"",
		mainItemLeft:"",
		mainItemRight:"",
		folderLeft:"",
		folderRight:"",
		itemLeft:"",
		itemRight:"",
		mainSpacing:0,
		subSpacing:0,
		delay:500,
		zIndexStart:1000,
		zIndexInc:5,
		subMenuHeader:null,
		subMenuFooter:null,
		offsetHMainAdjust:[0,0],
		offsetVMainAdjust:[0,0],
		offsetSubAdjust:[0,0],
		clickOpen:1,
		effect:null};
		
var _cmIDCount			= 0;
var _cmIDName			= "cmSubMenuID";
var _cmTimeOut			= null;
var _cmCurrentItem		= null;
var _cmNoAction			= new Object();
var _cmNoClick			= new Object();
var _cmSplit			= new Object();
var _cmMenuList			= new Array();
var _cmItemList			= new Array();
var _cmFrameList		= new Array();
var _cmFrameListSize	= 0;
var _cmFrameIDCount		= 0;
var _cmFrameMasking		= true;
var _cmClicked			= false;
var _cmHideObjects		= 0;

function cmClone(nodeProperties){
var returnVal	= new Object();
for(v in nodeProperties){
	returnVal[v]=nodeProperties[v]
}
	return returnVal
}

function cmAllocMenu(id,menu,orient,nodeProperties,prefix){
	var info	= new Object();
	info.div	= id;
	info.menu	= menu;
	info.orient	= orient;
	info.nodeProperties=nodeProperties;
	info.prefix	= prefix;
	var menuID	= _cmMenuList.length;
	_cmMenuList[menuID]=info;
	return menuID
}

function cmAllocFrame(){
	if(_cmFrameListSize>0)
	{
		return cmGetObject(_cmFrameList[--_cmFrameListSize])
	}
var frameObj 			= document.createElement("iframe");
var id					= _cmFrameIDCount++;
frameObj.id				= "cmFrame"+id;
frameObj.frameBorder	= "0";
frameObj.style.display	= "none";
frameObj.src="javascript:false";
document.body.appendChild(frameObj);
frameObj.style.filter	= "alpha(opacity=0)";
frameObj.style.zIndex	= 99;
frameObj.style.position	= "absolute";
frameObj.style.border	= "0";
frameObj.scrolling="no";
return frameObj
}
function cmFreeFrame(frameObj){
	_cmFrameList[_cmFrameListSize++]=frameObj.id
}
function cmNewID(){
	return _cmIDName+(++_cmIDCount)
}
function cmActionItem(item,isMain,idSub,menuInfo,menuID){
	_cmItemList[_cmItemList.length]=item;
	var index=_cmItemList.length-1;
	idSub=(!idSub)?"null":("'"+idSub+"'");
	var clickOpen=menuInfo.nodeProperties.clickOpen;
	var onClick=(clickOpen==3)||(clickOpen==2&&isMain);
	var param="this,"+isMain+","+idSub+","+menuID+","+index;
	var returnStr;
	if(onClick){
		returnStr=' onmouseover="cmItemMouseOver('+param+',false)" onmousedown="cmItemMouseDownOpenSub ('+param+')"'
	}else{
		returnStr=' onmouseover="cmItemMouseOverOpenSub ('+param+')" onmousedown="cmItemMouseDown ('+param+')"'
	}
	return returnStr+' onmouseout="cmItemMouseOut ('+param+')" onmouseup="cmItemMouseUp ('+param+')"'
}
function cmNoClickItem(item,isMain,idSub,menuInfo,menuID){
	_cmItemList[_cmItemList.length]=item;
	var index=_cmItemList.length-1;
	idSub=(!idSub)?"null":("'"+idSub+"'");
	var param="this,"+isMain+","+idSub+","+menuID+","+index;
	return' onmouseover="cmItemMouseOver ('+param+')" onmouseout="cmItemMouseOut ('+param+')"'
}
function cmNoActionItem(item){
	return item[1]
}
function cmSplitItem(prefix,isMain,vertical){
	var classStr="cm"+prefix;
	if(isMain){
		classStr+="Main";
		if(vertical){
			classStr+="HSplit"
		}else{
			classStr+="VSplit"
		}
	}else{
		classStr+="HSplit"
	}
	return eval(classStr)
}
function cmDrawSubMenu(subMenu,prefix,id,nodeProperties,zIndexStart,menuInfo,menuID){
	var str='<div class="'+prefix+'SubMenu" id="'+id+'" style="z-index:'+zIndexStart+'position: absolute; top: 0px; left: 0px;">';
	if(nodeProperties.subMenuHeader){
		str+=nodeProperties.subMenuHeader
	}
	str+='<table summary="sub menu" id="'+id+'Table" cellspacing="'+nodeProperties.subSpacing+'" class="'+prefix+'SubMenuTable">';
	var strSub="";
	var item;
	var idSub;
	var hasChild;
	var i;
	var classStr;
	for(i=5;i<subMenu.length;++i){
		item=subMenu[i];
		if(!item){
			continue
		}
		if(item==_cmSplit){
			item=cmSplitItem(prefix,0,true)
		}
		item.parentItem=subMenu;
		item.subMenuID=id;hasChild=(item.length>5);
		idSub=hasChild?cmNewID():null;
		str+='<tr class="'+prefix+'MenuItem"';
		if(item[0]!=_cmNoClick){
			str+=cmActionItem(item,0,idSub,menuInfo,menuID)
		}else{
			str+=cmNoClickItem(item,0,idSub,menuInfo,menuID)
		}
		str+=">";
		if(item[0]==_cmNoAction||item[0]==_cmNoClick){
			str+=cmNoActionItem(item);
			str+="</tr>";
			continue
		}
		classStr=prefix+"Menu";
		classStr+=hasChild?"Folder":"Item";
		str+='<td class="'+classStr+'Left">';
		if(item[0]!=null){
			str+=item[0]
		}else{
			str+=hasChild?nodeProperties.folderLeft:nodeProperties.itemLeft
		}
		str+='</td><td class="'+classStr+'Text">'+item[1];
		str+='</td><td class="'+classStr+'Right">';
		if(hasChild){
			str+=nodeProperties.folderRight;
			strSub+=cmDrawSubMenu(item,prefix,idSub,nodeProperties,zIndexStart+nodeProperties.zIndexInc,menuInfo,menuID)
		}else{
			str+=nodeProperties.itemRight
		}
		str+="</td></tr>"
		}
		str+="</table>";
		if(nodeProperties.subMenuFooter){
			str+=nodeProperties.subMenuFooter
		}
		str+="</div>"+strSub;
		return str
		}
		function cmDraw(id,menu,orient,nodeProperties,prefix){
			var obj=cmGetObject(id);
			
			if(!prefix){
				prefix=nodeProperties.prefix
			}
			if(!prefix){
				prefix=""
			}
			if(!nodeProperties){
				nodeProperties=_cmNodeProperties
			}
			if(!orient){
				orient="hbr"
			}
			var  menuID		= cmAllocMenu(id,menu,orient,nodeProperties,prefix);
			var menuInfo	= _cmMenuList[menuID];
			
			if(!nodeProperties.delay){
				nodeProperties.delay=_cmNodeProperties.delay
			}
			if(!nodeProperties.clickOpen){
				nodeProperties.clickOpen=_cmNodeProperties.clickOpen
			}
			if(!nodeProperties.zIndexStart){
				nodeProperties.zIndexStart=_cmNodeProperties.zIndexStart
			}
			if(!nodeProperties.zIndexInc){
				nodeProperties.zIndexInc=_cmNodeProperties.zIndexInc
			}
			if(!nodeProperties.offsetHMainAdjust){
				nodeProperties.offsetHMainAdjust=_cmNodeProperties.offsetHMainAdjust
			}
			if(!nodeProperties.offsetVMainAdjust){
				nodeProperties.offsetVMainAdjust=_cmNodeProperties.offsetVMainAdjust
			}
			if(!nodeProperties.offsetSubAdjust){
				nodeProperties.offsetSubAdjust=_cmNodeProperties.offsetSubAdjust
			}
			
			menuInfo.cmFrameMasking=_cmFrameMasking;
			var str		= '<table summary="main menu" class="'+prefix+'Menu" cellspacing="'+nodeProperties.mainSpacing+'">';
			var strSub	= "";
			var vertical;
			if(orient.charAt(0)=="h"){
				str+="<tr>";
				vertical=false
			}else{
				vertical=true
			}
			var i;
			var item;
			var idSub;
			var hasChild;
			var classStr;
			for(i=0;i<menu.length;++i){
				item=menu[i];
				if(!item){continue}
				item.menu=menu;
				item.subMenuID=id;
				str+=vertical?"<tr":"<td";
				str+=' class="'+prefix+'MainItem"';
				hasChild=(item.length>5);
				idSub=hasChild?cmNewID():null;
				str+=cmActionItem(item,1,idSub,menuInfo,menuID)+">";
				
				if(item==_cmSplit)
				{
					item=cmSplitItem(prefix,1,vertical)
				}
				if(item[0]==_cmNoAction||item[0]==_cmNoClick)
				{
					str+=cmNoActionItem(item);
					str+=vertical?"</tr>":"</td>";
					continue
				}
				
				classStr=prefix+"Main"+(hasChild?"Folder":"Item");
				str+=vertical?"<td":"<span";str+=' class="'+classStr+'Left">';
				str+=(item[0]==null)?(hasChild?nodeProperties.mainFolderLeft:nodeProperties.mainItemLeft):item[0];
				str+=vertical?"</td>":"</span>";
				str+=vertical?"<td":"<span";
				str+=' class="'+classStr+'Text">';
				str+=item[1];
				str+=vertical?"</td>":"</span>";
				str+=vertical?"<td":"<span";
				str+=' class="'+classStr+'Right">';
				str+=hasChild?nodeProperties.mainFolderRight:nodeProperties.mainItemRight;
				str+=vertical?"</td>":"</span>";
				str+=vertical?"</tr>":"</td>";
					if(hasChild)
					{
						strSub+=cmDrawSubMenu(item,prefix,idSub,nodeProperties,nodeProperties.zIndexStart,menuInfo,menuID)
					}
				}
				
				if(!vertical){str+="</tr>"}
				str+="</table>"+strSub;
				obj.innerHTML=str
			}
			
			function cmDrawFromText(id,orient,nodeProperties,prefix){
					var domMenu=cmGetObject(id);
					var menu=null;
					for(var currentDomItem=domMenu.firstChild;currentDomItem;currentDomItem=currentDomItem.nextSibling){
						if(!currentDomItem.tagName){continue}
						var tag=currentDomItem.tagName.toLowerCase();
						if(tag!="ul"&&tag!="ol"){continue}
						menu=cmDrawFromTextSubMenu(currentDomItem);break}
						if(menu){cmDraw(id,menu,orient,nodeProperties,prefix)}
				}
				function cmDrawFromTextSubMenu(domMenu){
					var items=new Array();
					for(var currentDomItem=domMenu.firstChild;currentDomItem;currentDomItem=currentDomItem.nextSibling){
						if(!currentDomItem.tagName||currentDomItem.tagName.toLowerCase()!="li"){continue}
						if(currentDomItem.firstChild==null){items[items.length]=_cmSplit;continue}
						var item=new Array();
						var currentItem=currentDomItem.firstChild;
						var hasAction=false;for(;currentItem;currentItem=currentItem.nextSibling){if(!currentItem.tagName){continue}if(currentItem.className=="cmNoClick"){item[0]=_cmNoClick;item[1]=getActionHTML(currentItem);hasAction=true;break}if(currentItem.className=="cmNoAction"){item[0]=_cmNoAction;item[1]=getActionHTML(currentItem);hasAction=true;break}var tag=currentItem.tagName.toLowerCase();if(tag!="span"){continue}if(!currentItem.firstChild){item[0]=null}else{item[0]=currentItem.innerHTML}currentItem=currentItem.nextSibling;break}if(hasAction){items[items.length]=item;continue}if(!currentItem){continue}for(;currentItem;currentItem=currentItem.nextSibling){if(!currentItem.tagName){continue}var tag=currentItem.tagName.toLowerCase();if(tag=="a"){item[1]=currentItem.innerHTML;item[2]=currentItem.href;item[3]=currentItem.target;item[4]=currentItem.title;if(item[4]==""){item[4]=null}}else{if(tag=="span"||tag=="div"){item[1]=currentItem.innerHTML;item[2]=null;item[3]=null;item[4]=null}}break}for(;currentItem;currentItem=currentItem.nextSibling){if(!currentItem.tagName){continue}var tag=currentItem.tagName.toLowerCase();if(tag!="ul"&&tag!="ol"){continue}var subMenuItems=cmDrawFromTextSubMenu(currentItem);for(i=0;i<subMenuItems.length;++i){item[i+5]=subMenuItems[i]}break}items[items.length]=item}return items}function getActionHTML(htmlNode){var returnVal="<td></td><td></td><td></td>";var currentDomItem;for(currentDomItem=htmlNode.firstChild;currentDomItem;currentDomItem=currentDomItem.nextSibling){if(currentDomItem.tagName&&currentDomItem.tagName.toLowerCase()=="table"){break}}if(!currentDomItem){return returnVal}for(currentDomItem=currentDomItem.firstChild;currentDomItem;currentDomItem=currentDomItem.nextSibling){if(currentDomItem.tagName&&currentDomItem.tagName.toLowerCase()=="tbody"){break}}if(!currentDomItem){return returnVal}for(currentDomItem=currentDomItem.firstChild;currentDomItem;currentDomItem=currentDomItem.nextSibling){if(currentDomItem.tagName&&currentDomItem.tagName.toLowerCase()=="tr"){break}}if(!currentDomItem){return returnVal}return currentDomItem.innerHTML}function cmGetMenuItem(item){if(!item.subMenuID){return null}var subMenu=cmGetObject(item.subMenuID);if(item.menu){var menu=item.menu;subMenu=subMenu.firstChild.firstChild.firstChild.firstChild;var i;for(i=0;i<menu.length;++i){if(menu[i]==item){return subMenu}subMenu=subMenu.nextSibling}}else{if(item.parentItem){var menu=item.parentItem;var table=cmGetObject(item.subMenuID+"Table");if(!table){return null}subMenu=table.firstChild.firstChild;var i;for(i=5;i<menu.length;++i){if(menu[i]==item){return subMenu}subMenu=subMenu.nextSibling}}}return null}function cmDisableItem(item,prefix){if(!item){return }var menuItem=cmGetMenuItem(item);if(!menuItem){return }if(item.menu){menuItem.className=prefix+"MainItemDisabled"}else{menuItem.className=prefix+"MenuItemDisabled"}item.isDisabled=true}function cmEnableItem(item,prefix){if(!item){return }var menuItem=cmGetMenuItem(item);if(!menuItem){return }if(item.menu){menu.className=prefix+"MainItem"}else{menu.className=prefix+"MenuItem"}item.isDisabled=true}function cmItemMouseOver(obj,isMain,idSub,menuID,index,calledByOpenSub){if(!calledByOpenSub&&_cmClicked){cmItemMouseOverOpenSub(obj,isMain,idSub,menuID,index);return }clearTimeout(_cmTimeOut);if(_cmItemList[index].isDisabled){return }var prefix=_cmMenuList[menuID].prefix;if(!obj.cmMenuID){obj.cmMenuID=menuID;obj.cmIsMain=isMain}var thisMenu=cmGetThisMenu(obj,prefix);if(!thisMenu.cmItems){thisMenu.cmItems=new Array()}var i;for(i=0;i<thisMenu.cmItems.length;++i){if(thisMenu.cmItems[i]==obj){break}}if(i==thisMenu.cmItems.length){thisMenu.cmItems[i]=obj}if(_cmCurrentItem){if(_cmCurrentItem==obj||_cmCurrentItem==thisMenu){var item=_cmItemList[index];cmSetStatus(item);return }var thatMenuInfo=_cmMenuList[_cmCurrentItem.cmMenuID];var thatPrefix=thatMenuInfo.prefix;var thatMenu=cmGetThisMenu(_cmCurrentItem,thatPrefix);if(thatMenu!=thisMenu.cmParentMenu){if(_cmCurrentItem.cmIsMain){_cmCurrentItem.className=thatPrefix+"MainItem"}else{_cmCurrentItem.className=thatPrefix+"MenuItem"}if(thatMenu.id!=idSub){cmHideMenu(thatMenu,thisMenu,thatMenuInfo)}}}_cmCurrentItem=obj;cmResetMenu(thisMenu,prefix);var item=_cmItemList[index];var isDefaultItem=cmIsDefaultItem(item);if(isDefaultItem){if(isMain){obj.className=prefix+"MainItemHover"}else{obj.className=prefix+"MenuItemHover"}}cmSetStatus(item)}function cmItemMouseOverOpenSub(obj,isMain,idSub,menuID,index){clearTimeout(_cmTimeOut);if(_cmItemList[index].isDisabled){return }cmItemMouseOver(obj,isMain,idSub,menuID,index,true);if(idSub){var subMenu=cmGetObject(idSub);var menuInfo=_cmMenuList[menuID];var orient=menuInfo.orient;var prefix=menuInfo.prefix;cmShowSubMenu(obj,isMain,subMenu,menuInfo)}}function cmItemMouseOut(obj,isMain,idSub,menuID,index){var delayTime=_cmMenuList[menuID].nodeProperties.delay;_cmTimeOut=window.setTimeout("cmHideMenuTime ()",delayTime);window.defaultStatus=""}function cmItemMouseDown(obj,isMain,idSub,menuID,index){if(_cmItemList[index].isDisabled){return }if(cmIsDefaultItem(_cmItemList[index])){var prefix=_cmMenuList[menuID].prefix;if(obj.cmIsMain){obj.className=prefix+"MainItemActive"}else{obj.className=prefix+"MenuItemActive"}}}function cmItemMouseDownOpenSub(obj,isMain,idSub,menuID,index){if(_cmItemList[index].isDisabled){return }_cmClicked=true;cmItemMouseDown(obj,isMain,idSub,menuID,index);if(idSub){var subMenu=cmGetObject(idSub);var menuInfo=_cmMenuList[menuID];cmShowSubMenu(obj,isMain,subMenu,menuInfo)}}function cmItemMouseUp(obj,isMain,idSub,menuID,index){if(_cmItemList[index].isDisabled){return }var item=_cmItemList[index];var link=null,target="_self";if(item.length>2){link=item[2]}if(item.length>3&&item[3]){target=item[3]}if(link!=null){_cmClicked=false;window.open(link,target)}var menuInfo=_cmMenuList[menuID];var prefix=menuInfo.prefix;var thisMenu=cmGetThisMenu(obj,prefix);var hasChild=(item.length>5);if(!hasChild){if(cmIsDefaultItem(item)){if(obj.cmIsMain){obj.className=prefix+"MainItem"}else{obj.className=prefix+"MenuItem"}}cmHideMenu(thisMenu,null,menuInfo)}else{if(cmIsDefaultItem(item)){if(obj.cmIsMain){obj.className=prefix+"MainItemHover"}else{obj.className=prefix+"MenuItemHover"}}}}function cmMoveSubMenu(obj,isMain,subMenu,menuInfo){var orient=menuInfo.orient;var offsetAdjust;if(isMain){if(orient.charAt(0)=="h"){offsetAdjust=menuInfo.nodeProperties.offsetHMainAdjust}else{offsetAdjust=menuInfo.nodeProperties.offsetVMainAdjust}}else{offsetAdjust=menuInfo.nodeProperties.offsetSubAdjust}if(!isMain&&orient.charAt(0)=="h"){orient="v"+orient.charAt(1)+orient.charAt(2)}var mode=String(orient);var p=subMenu.offsetParent;var subMenuWidth=cmGetWidth(subMenu);var horiz=cmGetHorizontalAlign(obj,mode,p,subMenuWidth);if(mode.charAt(0)=="h"){if(mode.charAt(1)=="b"){subMenu.style.top=(cmGetYAt(obj,p)+cmGetHeight(obj)+offsetAdjust[1])+"px"}else{subMenu.style.top=(cmGetYAt(obj,p)-cmGetHeight(subMenu)-offsetAdjust[1])+"px"}if(horiz=="r"){subMenu.style.left=(cmGetXAt(obj,p)+offsetAdjust[0])+"px"}else{subMenu.style.left=(cmGetXAt(obj,p)+cmGetWidth(obj)-subMenuWidth-offsetAdjust[0])+"px"}}else{if(horiz=="r"){subMenu.style.left=(cmGetXAt(obj,p)+cmGetWidth(obj)+offsetAdjust[0])+"px"}else{subMenu.style.left=(cmGetXAt(obj,p)-subMenuWidth-offsetAdjust[0])+"px"}if(mode.charAt(1)=="b"){subMenu.style.top=(cmGetYAt(obj,p)+offsetAdjust[1])+"px"}else{subMenu.style.top=(cmGetYAt(obj,p)+cmGetHeight(obj)-cmGetHeight(subMenu)+offsetAdjust[1])+"px"}}
/*@cc_on
		@if (@_jscript_version >= 5.5)
			if (menuInfo.cmFrameMasking)
			{
				if (!subMenu.cmFrameObj)
				{
					var frameObj = cmAllocFrame ();
					subMenu.cmFrameObj = frameObj;
				}

				var frameObj = subMenu.cmFrameObj;
				frameObj.style.zIndex = subMenu.style.zIndex - 1;
				frameObj.style.left = (cmGetX (subMenu) - cmGetX (frameObj.offsetParent)) + 'px';
				frameObj.style.top = (cmGetY (subMenu)  - cmGetY (frameObj.offsetParent)) + 'px';
				frameObj.style.width = cmGetWidth (subMenu) + 'px';
				frameObj.style.height = cmGetHeight (subMenu) + 'px';
				frameObj.style.display = 'block';
			}
		@end
	@*/
if(horiz!=orient.charAt(2)){orient=orient.charAt(0)+orient.charAt(1)+horiz}return orient}function cmGetHorizontalAlign(obj,mode,p,subMenuWidth){var horiz=mode.charAt(2);if(!(document.body)){return horiz}var body=document.body;var browserLeft;var browserRight;if(window.innerWidth){browserLeft=window.pageXOffset;browserRight=window.innerWidth+browserLeft}else{if(body.clientWidth){browserLeft=body.clientLeft;browserRight=body.clientWidth+browserLeft}else{return horiz}}if(mode.charAt(0)=="h"){if(horiz=="r"&&(cmGetXAt(obj)+subMenuWidth)>browserRight){horiz="l"}if(horiz=="l"&&(cmGetXAt(obj)+cmGetWidth(obj)-subMenuWidth)<browserLeft){horiz="r"}return horiz}else{if(horiz=="r"&&(cmGetXAt(obj,p)+cmGetWidth(obj)+subMenuWidth)>browserRight){horiz="l"}if(horiz=="l"&&(cmGetXAt(obj,p)-subMenuWidth)<browserLeft){horiz="r"}return horiz}}function cmShowSubMenu(obj,isMain,subMenu,menuInfo){var prefix=menuInfo.prefix;if(!subMenu.cmParentMenu){var thisMenu=cmGetThisMenu(obj,prefix);subMenu.cmParentMenu=thisMenu;if(!thisMenu.cmSubMenu){thisMenu.cmSubMenu=new Array()}thisMenu.cmSubMenu[thisMenu.cmSubMenu.length]=subMenu}var effectInstance=subMenu.cmEffect;if(effectInstance){effectInstance.showEffect(true)}else{var orient=cmMoveSubMenu(obj,isMain,subMenu,menuInfo);subMenu.cmOrient=orient;var forceShow=false;if(subMenu.style.visibility!="visible"&&menuInfo.nodeProperties.effect){try{effectInstance=menuInfo.nodeProperties.effect.getInstance(subMenu,orient);effectInstance.showEffect(false)}catch(e){forceShow=true;subMenu.cmEffect=null}}else{forceShow=true}if(forceShow){subMenu.style.visibility="visible";
/*@cc_on
				@if (@_jscript_version >= 5.5)
					if (subMenu.cmFrameObj)
						subMenu.cmFrameObj.style.display = 'block';
				@end
			@*/
}}if(!_cmHideObjects){_cmHideObjects=2;try{if(window.opera){if(parseInt(navigator.appVersion)<9){_cmHideObjects=1}}}catch(e){}}if(_cmHideObjects==1){if(!subMenu.cmOverlap){subMenu.cmOverlap=new Array()}cmHideControl("IFRAME",subMenu);cmHideControl("OBJECT",subMenu)}}function cmResetMenu(thisMenu,prefix){if(thisMenu.cmItems){var i;var str;var items=thisMenu.cmItems;for(i=0;i<items.length;++i){if(items[i].cmIsMain){if(items[i].className==(prefix+"MainItemDisabled")){continue}}else{if(items[i].className==(prefix+"MenuItemDisabled")){continue}}if(items[i].cmIsMain){str=prefix+"MainItem"}else{str=prefix+"MenuItem"}if(items[i].className!=str){items[i].className=str}}}}function cmHideMenuTime(){_cmClicked=false;if(_cmCurrentItem){var menuInfo=_cmMenuList[_cmCurrentItem.cmMenuID];var prefix=menuInfo.prefix;cmHideMenu(cmGetThisMenu(_cmCurrentItem,prefix),null,menuInfo);_cmCurrentItem=null}}function cmHideThisMenu(thisMenu,menuInfo){var effectInstance=thisMenu.cmEffect;if(effectInstance){effectInstance.hideEffect(true)}else{thisMenu.style.visibility="hidden";thisMenu.style.top="0px";thisMenu.style.left="0px";thisMenu.cmOrient=null;
/*@cc_on
			@if (@_jscript_version >= 5.5)
				if (thisMenu.cmFrameObj)
				{
					var frameObj = thisMenu.cmFrameObj;
					frameObj.style.display = 'none';
					frameObj.style.width = '1px';
					frameObj.style.height = '1px';
					thisMenu.cmFrameObj = null;
					cmFreeFrame (frameObj);
				}
			@end
		@*/
}cmShowControl(thisMenu);thisMenu.cmItems=null}function cmHideMenu(thisMenu,currentMenu,menuInfo){var prefix=menuInfo.prefix;var str=prefix+"SubMenu";if(thisMenu.cmSubMenu){var i;for(i=0;i<thisMenu.cmSubMenu.length;++i){cmHideSubMenu(thisMenu.cmSubMenu[i],menuInfo)}}while(thisMenu&&thisMenu!=currentMenu){cmResetMenu(thisMenu,prefix);if(thisMenu.className==str){cmHideThisMenu(thisMenu,menuInfo)}else{break}thisMenu=cmGetThisMenu(thisMenu.cmParentMenu,prefix)}}function cmHideSubMenu(thisMenu,menuInfo){if(thisMenu.style.visibility=="hidden"){return }if(thisMenu.cmSubMenu){var i;for(i=0;i<thisMenu.cmSubMenu.length;++i){cmHideSubMenu(thisMenu.cmSubMenu[i],menuInfo)}}var prefix=menuInfo.prefix;cmResetMenu(thisMenu,prefix);cmHideThisMenu(thisMenu,menuInfo)}function cmHideControl(tagName,subMenu){var x=cmGetX(subMenu);var y=cmGetY(subMenu);var w=subMenu.offsetWidth;var h=subMenu.offsetHeight;var i;for(i=0;i<document.all.tags(tagName).length;++i){var obj=document.all.tags(tagName)[i];if(!obj||!obj.offsetParent){continue}var ox=cmGetX(obj);var oy=cmGetY(obj);var ow=obj.offsetWidth;var oh=obj.offsetHeight;if(ox>(x+w)||(ox+ow)<x){continue}if(oy>(y+h)||(oy+oh)<y){continue}if(obj.style.visibility=="hidden"){continue}subMenu.cmOverlap[subMenu.cmOverlap.length]=obj;obj.style.visibility="hidden"}}function cmShowControl(subMenu){if(subMenu.cmOverlap){var i;for(i=0;i<subMenu.cmOverlap.length;++i){subMenu.cmOverlap[i].style.visibility=""}}subMenu.cmOverlap=null}function cmGetThisMenu(obj,prefix){var str1=prefix+"SubMenu";var str2=prefix+"Menu";while(obj){if(obj.className==str1||obj.className==str2){return obj}obj=obj.parentNode}return null}function cmTimeEffect(menuID,show,delayTime){window.setTimeout('cmCallEffect("'+menuID+'",'+show+")",delayTime)}function cmCallEffect(menuID,show){var menu=cmGetObject(menuID);if(!menu||!menu.cmEffect){return }try{if(show){menu.cmEffect.showEffect(false)}else{menu.cmEffect.hideEffect(false)}}catch(e){}}function cmIsDefaultItem(item){if(item==_cmSplit||item[0]==_cmNoAction||item[0]==_cmNoClick){return false}return true}function cmGetObject(id){if(document.all){return document.all[id]}return document.getElementById(id)}function cmGetWidth(obj){var width=obj.offsetWidth;if(width>0||!cmIsTRNode(obj)){return width}if(!obj.firstChild){return 0}return obj.lastChild.offsetLeft-obj.firstChild.offsetLeft+cmGetWidth(obj.lastChild)}function cmGetHeight(obj){var height=obj.offsetHeight;if(height>0||!cmIsTRNode(obj)){return height}if(!obj.firstChild){return 0}return obj.firstChild.offsetHeight}function cmGetX(obj){if(!obj){return 0}var x=0;do{x+=obj.offsetLeft;obj=obj.offsetParent}while(obj);return x}function cmGetXAt(obj,elm){var x=0;while(obj&&obj!=elm){x+=obj.offsetLeft;obj=obj.offsetParent}if(obj==elm){return x}return x-cmGetX(elm)}function cmGetY(obj){if(!obj){return 0}var y=0;do{y+=obj.offsetTop;obj=obj.offsetParent}while(obj);return y}function cmIsTRNode(obj){var tagName=obj.tagName;return tagName=="TR"||tagName=="tr"||tagName=="Tr"||tagName=="tR"}function cmGetYAt(obj,elm){var y=0;if(!obj.offsetHeight&&cmIsTRNode(obj)){var firstTR=obj.parentNode.firstChild;obj=obj.firstChild;y-=firstTR.firstChild.offsetTop}while(obj&&obj!=elm){y+=obj.offsetTop;obj=obj.offsetParent}if(obj==elm){return y}return y-cmGetY(elm)}function cmSetStatus(item){var descript="";if(item.length>4){descript=(item[4]!=null)?item[4]:(item[2]?item[2]:descript)}else{if(item.length>2){descript=(item[2]?item[2]:descript)}}window.defaultStatus=descript}function cmGetProperties(obj){if(obj==undefined){return"undefined"}if(obj==null){return"null"}var msg=obj+":\n";var i;for(i in obj){msg+=i+" = "+obj[i]+"; "}return msg};