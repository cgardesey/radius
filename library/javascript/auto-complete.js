




DHTMLSuite.autoComplete=function(){
   // definations
   var layoutCss;
   var ajaxBox_offsetX;
   var ajaxBox_offsetY;   
   var minimumLettersBeforeLookup;   // Number of letters entered before a lookup is performed.
   
   var ajax_list_currentLetters;
   var ajax_list_cachedLists
   var ajax_optionDiv;
   var ajax_list_MSIE;
   var ajax_list_activeItem;
   var ajax_list_objects;   
   var ajax_list_activeInput;
   var ajax_list_activeItem;
   var ajax_list_optionDivFirstItem
   var ajax_optionDiv;
   var ajax_optionDiv_iframe;   
   
   // set values
   this.layoutCss='auto-complete.css';
   this.ajaxBox_offsetX = 0;
   this.ajaxBox_offsetY = 0;
   this.ajax_list_currentLetters = new Array();
   this.ajax_list_cachedLists = new Array();
   this.ajax_optionDiv = false;
   this.ajax_list_MSIE = false;
   if (navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('Opera')<0)
      this.ajax_list_MSIE=true;
   this.ajax_list_activeItem;
   this.ajax_list_objects = new Array();   
   this.ajax_list_activeInput = false;
   this.ajax_list_activeItem;
   this.ajax_list_optionDivFirstItem = false;
   this.ajax_optionDiv = false;
   this.ajax_optionDiv_iframe = false;   
   
   var ajax_list_MSIE = false;
   if(navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('Opera')<0) ajax_list_MSIE=true;
      
   this.minimumLettersBeforeLookup = 1;
      
      
   // check modules
   try{
   if(!standardObjectsCreated)DHTMLSuite.createStandardObjects();   
// This line starts all the init methods
   }catch(e){
   alert('You need to include the dhtmlSuite-common.js file');
   }
   // load object to variableStorage
   this.objectIndex = DHTMLSuite.variableStorage.arrayDSObjects.length;;
   DHTMLSuite.variableStorage.arrayDSObjects[this.objectIndex] = this;   
   document.body.setAttribute("objectIndex",this.objectIndex); // set attribute to body for keyNavigation
   
   DHTMLSuite.commonObj.loadCSS(this.layoutCss); // load css
         
   
}

DHTMLSuite.autoComplete.prototype={
   
   
   
   // {{{ add(inputObjId,externalFile)
    
    add:function(inputObjId,externalFile,cssTag,autocompleteTag)
    {
       var refObject = DHTMLSuite.variableStorage.arrayDSObjects[this.objectIndex];
       eval("document.getElementById('"+inputObjId+"').onkeyup = function(e) { refObject.__showOptions(e,'"+ inputObjId +"','"+ externalFile +"','"+ cssTag +"','"+ autocompleteTag +"') };");
       DHTMLSuite.commonObj.__addEventEl(document.getElementById(inputObjId));   
    }
// }}}
   ,      
// {{{ setLayoutCss(newCssFileName)
       
   setLayoutCss:function(newCssFileName)
   {
      this.layoutCss=newCssFileName;
   }   
// }}}
   ,   
// {{{ __showOptions(inputObj,externalFile,e)
              
   __showOptions:function(e, inputObjId,externalFile,cssTag,autocompleteTag)
   {
      var inputObj = document.getElementById(inputObjId); // input object
      var ind = this.objectIndex;   // class object index

      if(document.all)e = event;      
      if(e.keyCode==13 || e.keyCode==9)return;
      if(this.ajax_list_currentLetters[inputObj.name]==inputObj.value) return;      
      if(!this.ajax_list_cachedLists[inputObj.id]) this.ajax_list_cachedLists[inputObj.id] = new Array();
      
      this.ajax_list_currentLetters[inputObj.name] = inputObj.value;
      if(!this.ajax_optionDiv){
         this.ajax_optionDiv = document.createElement('DIV');
         this.ajax_optionDiv.id = 'ajax_listOfOptions'+cssTag;
         document.body.appendChild(this.ajax_optionDiv);
         
         if(this.ajax_list_MSIE){
            this.ajax_optionDiv_iframe = document.createElement('IFRAME');
            this.ajax_optionDiv_iframe.border='0';
            this.ajax_optionDiv_iframe.style.width = this.ajax_optionDiv.clientWidth + 'px';
            this.ajax_optionDiv_iframe.style.height = this.ajax_optionDiv.clientHeight + 'px';
            this.ajax_optionDiv_iframe.id = 'ajax_listOfOptions_iframe';
            
            document.body.appendChild(this.ajax_optionDiv_iframe);
         }
         
         var allInputs = document.getElementsByTagName('INPUT');
         for(var no=0;no<allInputs.length;no++){
            if(!allInputs[no].onkeyup)allInputs[no].onfocus = this.__ajaxOptionsHide;
         }         
         var allSelects = document.getElementsByTagName('SELECT');
         for(var no=0;no<allSelects.length;no++){
            allSelects[no].onfocus = this.__ajaxOptionsHide;
         }

         var oldonkeydown=document.body.onkeydown;
         if(typeof oldonkeydown!='function'){
            document.body.onkeydown=this.__ajaxOptionKeyNavigation;
         }else{
            document.body.onkeydown=function(){
               oldonkeydown();
               this.__ajaxOptionKeyNavigation() ;}
         }
         var oldonresize=document.body.onresize;
         if(typeof oldonresize!='function'){
            document.body.onresize=function() {this.__ajaxOptionResize(inputObj); };
         }else{
            document.body.onresize=function(){oldonresize();
            this.__ajaxOptionResize(inputObj) ;}
         }
            
      }
      
      if(inputObj.value.length < this.minimumLettersBeforeLookup){
         this.__ajaxOptionsHide();
         return;
      }
            

      this.ajax_optionDiv.style.top = (this.__ajaxGetTopPos(inputObj) + inputObj.offsetHeight + this.ajaxBox_offsetY) + 'px';
      this.ajax_optionDiv.style.left = (this.__ajaxGetLeftPos(inputObj) + this.ajaxBox_offsetX) + 'px';
      if(this.ajax_optionDiv_iframe){
         this.ajax_optionDiv_iframe.style.left = this.ajax_optionDiv.style.left;
         this.ajax_optionDiv_iframe.style.top = this.ajax_optionDiv.style.top;         
      }
      
      this.ajax_list_activeInput = inputObj;
      this.ajax_optionDiv.onselectstart =  this.__ajaxListCancelEvent;      
      if(this.ajax_list_cachedLists[inputObj.id][inputObj.value.toLowerCase()]){         
         this.__ajaxOptionListBuildList(inputObj.value,inputObj.id); //batur                  
      }else{                  
         this.ajax_optionDiv.innerHTML = '';
         var ajaxIndex = this.ajax_list_objects.length;
         this.ajax_list_objects[ajaxIndex] = new sack();
         var url = externalFile + '?' + autocompleteTag + '=' + inputObj.value.replace(" ","+");
         //alert(url);
         this.ajax_list_objects[ajaxIndex].requestFile = url;   // Specifying which file to get
         this.ajax_list_objects[ajaxIndex].onCompletion = function(){ DHTMLSuite.variableStorage.arrayDSObjects[ind].__ajaxOptionListShowContent(ajaxIndex,inputObj); };   // Specify function that will be executed after file has been found
         this.ajax_list_objects[ajaxIndex].runAJAX();      // Execute AJAX function      
      }
      
         
   }
// }}}
   ,
   
// {{{ __ajaxOptionsHide()
       
   __ajaxOptionsHide:function()
   {
      if(this.ajax_optionDiv) this.ajax_optionDiv.style.display='none';   
      if(this.ajax_optionDiv_iframe) this.ajax_optionDiv_iframe.style.display='none';
   }
// }}}
   ,
// {{{ __ajaxOptionKeyNavigation(e)
       
   __ajaxOptionKeyNavigation:function(e)
   {   // this: document.body      
      if(document.all)e = event;   
      var objectIndex = document.body.getAttribute("objectIndex");
      var refObject = DHTMLSuite.variableStorage.arrayDSObjects[objectIndex];
      
      if(!refObject.ajax_optionDiv)return;
      if(refObject.ajax_optionDiv.style.display=='none')return;
      
      if(e.keyCode==38){   // Up arrow         
         if(!refObject.ajax_list_activeItem)return;
         if(refObject.ajax_list_activeItem && !refObject.ajax_list_activeItem.previousSibling)return;
         refObject.__ajaxOptionsRollOverActiveItem(refObject.ajax_list_activeItem.previousSibling,true);
      }
      
      if(e.keyCode==40){   // Down arrow
         if(!refObject.ajax_list_activeItem){
            refObject.__ajaxOptionsRollOverActiveItem(ajax_list_optionDivFirstItem,true);
         }else{
            if(!refObject.ajax_list_activeItem.nextSibling)return;
            refObject.__ajaxOptionsRollOverActiveItem(refObject.ajax_list_activeItem.nextSibling,true);
         }
      }
      
      if(e.keyCode==13 || e.keyCode==9){   // Enter key or tab key                  
         if(refObject.ajax_list_activeItem && refObject.ajax_list_activeItem.className=='optionDivSelected') refObject.__ajaxOptionSetValue(false,refObject.ajax_list_activeItem);
         if(e.keyCode==13)return false; else return true;
      }
      
      if(e.keyCode==27){   // Escape key
         refObject.__ajaxOptionsHide();         
      }
   }
// }}}
   ,
// {{{ __ajaxOptionsRollOverActiveItem(item,fromKeyBoard)
       

   __ajaxOptionsRollOverActiveItem:function(item,fromKeyBoard)
   {
      if(this.ajax_list_activeItem) this.ajax_list_activeItem.className='optionDiv';
      item.className='optionDivSelected';
      this.ajax_list_activeItem = item;
      
      if(fromKeyBoard){
         if(this.ajax_list_activeItem.offsetTop > this.ajax_optionDiv.offsetHeight){
            this.ajax_optionDiv.scrollTop = this.ajax_list_activeItem.offsetTop - this.ajax_optionDiv.offsetHeight + this.ajax_list_activeItem.offsetHeight + 2 ;
         }
         if(this.ajax_list_activeItem.offsetTop < this.ajax_optionDiv.scrollTop)
         {
            this.ajax_optionDiv.scrollTop = 0;   
         }
      }
   }
// }}}
   ,
// {{{ __ajaxOptionResize(inputObj)
       
   __ajaxOptionResize:function(inputObj)
   {
      this.ajax_optionDiv.style.top  = (this.__ajaxGetTopPos(inputObj) + inputObj.offsetHeight + this.ajaxBox_offsetY) + 'px';
      this.ajax_optionDiv.style.left = (this.__ajaxGetLeftPos(inputObj) + this.ajaxBox_offsetX) + 'px';
      if(this.ajax_optionDiv_iframe){
         this.ajax_optionDiv_iframe.style.left = this.ajax_optionDiv.style.left;
         this.ajax_optionDiv_iframe.style.top = this.ajax_optionDiv.style.top;         
      }      
      
   }   
// }}}
   ,
// {{{ __ajaxGetTopPos(inputObj)
       
   __ajaxGetTopPos:function(inputObj)
   {
      var returnValue = inputObj.offsetTop;
        while((inputObj = inputObj.offsetParent) != null){
           returnValue += inputObj.offsetTop;
        }
        return returnValue;
   }
// }}}
   ,
// {{{ __ajaxGetLeftPos(inputObj)
       
   __ajaxGetLeftPos:function(inputObj)
   {
      var returnValue = inputObj.offsetLeft;
        while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
    
        return returnValue;
   }
// }}}
   ,
// {{{ __ajaxOptionListShowContent(ajaxIndex,inputObj)
       
     __ajaxOptionListShowContent:function(ajaxIndex,inputObj)
   {      
      var letters = inputObj.value;
      var content = this.ajax_list_objects[ajaxIndex].response;
      var elements = content.split('|');
      this.ajax_list_cachedLists[inputObj.id][letters.toLowerCase()] = elements;
      this.__ajaxOptionListBuildList(letters,inputObj.id);
      
   }
// }}}
   ,
// {{{ __ajaxOptionListBuildList(letters,inputObjId)
       
   __ajaxOptionListBuildList:function(letters,inputObjId)
   {
      var ind = this.objectIndex;
      
      //alert(inputObjId);
      
      this.ajax_optionDiv.innerHTML = '';
      this.ajax_list_activeItem = false;
      if(this.ajax_list_cachedLists[inputObjId][letters.toLowerCase()].length<=1){
         this.__ajaxOptionsHide();
         return;         
      }
      
      this.ajax_list_optionDivFirstItem = false;
      var optionsAdded = false;
      for(var no=0;no<this.ajax_list_cachedLists[inputObjId][letters.toLowerCase()].length;no++){
         if(this.ajax_list_cachedLists[inputObjId][letters.toLowerCase()][no].length==0)continue;
         optionsAdded = true;
         var div = document.createElement('DIV');
         var items = this.ajax_list_cachedLists[inputObjId][letters.toLowerCase()][no].split(/###/gi);
         
         if(this.ajax_list_cachedLists[inputObjId][letters.toLowerCase()].length==1 && this.ajax_list_activeInput.value == items[0]){
            this.__ajaxOptionsHide();
            return;                  
         }
         
         div.innerHTML = items[items.length-1];
         div.id = items[0];
         div.className='optionDiv';
         div.setAttribute('objectIndex',ind);
         div.onmouseover = function(){ DHTMLSuite.variableStorage.arrayDSObjects[ind].__ajaxOptionsRollOverActiveItem(this,false) }         
         div.onclick = this.__ajaxOptionSetValue;
         if(!this.ajax_list_optionDivFirstItem) this.ajax_list_optionDivFirstItem = div;
         this.ajax_optionDiv.appendChild(div);
      }   
      if(optionsAdded){
         this.ajax_optionDiv.style.display='block';
         if(this.ajax_optionDiv_iframe) this.ajax_optionDiv_iframe.style.display='';
         this.__ajaxOptionsRollOverActiveItem(this.ajax_list_optionDivFirstItem,true);
      }
               
   }
// }}}
   ,
// {{{ __ajaxOptionSetValue(e,inputObj)
       

   __ajaxOptionSetValue:function(e,divOptionObj)
   {      
      // this : div element
      if(!divOptionObj)divOptionObj=this;
      var referenceToObject = DHTMLSuite.variableStorage.arrayDSObjects[divOptionObj.getAttribute('objectIndex')];      
      var tmpValue = divOptionObj.innerHTML;
      if (referenceToObject.ajax_list_MSIE) tmpValue = divOptionObj.innerText; else tmpValue = divOptionObj.textContent;
      if(!tmpValue)tmpValue = divOptionObj.innerHTML;
      referenceToObject.ajax_list_activeInput.value = tmpValue;
      if(document.getElementById(referenceToObject.ajax_list_activeInput.name + '_hidden'))document.getElementById(referenceToObject.ajax_list_activeInput.name + '_hidden').value = divOptionObj.id;
      referenceToObject.__ajaxOptionsHide();
                     
   }
// }}}
   ,
// {{{ __ajaxListCancelEvent()
       
   __ajaxListCancelEvent:function()
   {
      return false;
   }
   
   
} 

