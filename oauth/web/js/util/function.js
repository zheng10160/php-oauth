// getClass(classname)
// 获取指定拥有指定类名元素的集合
// classname 指定的类名
// 思路：
// 一 判断浏览器
// 	document.getElementsByClassName
// 二 true
// 	直接用document.getElementsByClassName()
//    false
//     已知方法来模拟：从所用的元素中筛选className是否等于指定的类名
//     classname obj.className
function getClass(classname,ranger){
	ranger= ranger===undefined?document:ranger;
	if(document.getElementsByClassName){
		return ranger.getElementsByClassName(classname);
	}else{
		var arr=ranger.getElementsByTagName('*');
		var newarr=[];
		for(var i=0;i<arr.length;i++){
			var flsg=check(arr[i].className,classname);
			if(flsg){
				newarr.push(arr[i]);
			}
		}
		return newarr;	
	}
}

function check(str,value){
	// str转换成数组，里面是否有value	
	var arr=str.split(' ');
	for(var i=0;i<arr.length;i++){
		if(arr[i]==value){
			return true;
		}
	}
	return false;
}

function $(select,ranger){
	ranger= ranger===undefined?document:ranger;
	var first= select.charAt(0);
	if(first=='.'){
		//通过类名
		return getClass(select.slice(1),ranger)
	}else if(first=='#'){
		//通过id
		return ranger.getElementById(select.slice(1));
	}else if(/^[a-zA-Z][a-zA-Z1-6]{0,8}$/.test(select)){
		//通过标签
		return ranger.getElementsByTagName(select);
	}
}





// 设置或获取obj的文本
function setText(obj,value){
	if(value){
		if(obj.innerText){
			return obj.innerText=value;
		}else{
			return obj.textContent=value;
		}
	}else{
		if(obj.innerText){
			return obj.innerText;
		}else{
			return obj.textContent;
		}
	}
}

// 获取某一个指定对象的某一个形式
function getStyle(obj,attr){
	if(window.getComputedStyle){
		return getComputedStyle(obj,null)[attr];
	}else{
		return obj.currentStyle[attr];
	}
}



// *********************************************************************************************************

// 获取元素及非空的文本


function getchilds(box,type){
	var childs=box.childNodes;
	var arr=[];
    var type=type||false;
    if(type){

    	for(var i=0;i<obj.length;i++){
	if (childs[i].nodeType==1||(childs[i].nodeType==3&&childs[i].nodeValue.trim().length!=0)){
			arr.push(childs[i]);
    }
	
    }
	return arr;
	}else{
	for(var i=0;i<childs.length;i++){
		if(childs[i].nodeType==1){
			arr.push(childs[i]);
		}
	}
	return arr;
	}

}

// 获取父元素的第一个非空的元素节点
function firstChild(obj){
	var  first=getchilds(obj)[0];
	
	return first;

}


// 获取元素的下一个兄弟元素


// 1获取兄弟元素
// 2判断一下兄弟元素 是否为元素节点
// 3若不是 更新一下next=next.Sibling
    function getNext(obj){			
			var next=obj.nextSibling;
			if(next===null){
				return obj;
			}
			while(next.nodeType!=1){
				next=next.nextSibling;
				if(next==null){
					return false;
				}
			}
			return next;
		}
	

// 获取上一个 兄弟元素元素节点
function getPrevious(obj){           
         var next=obj.PreviousSibling;
         if(next===null){
             return false;
         }
         while(next.nodeType==3||next.nodeType==8){
             next=next.PreviousSibling;
             if(next==null){
                 return false;
             }
         }
         return next;
     }




// 把一个特定的元素 node插入到指定元素 newnode的后面

// insertAfter   
// 获取newnode的下一个元素节点
// 获取newnode父元素的



function insertAfter(node,newnode){
	var sibling=newnode.nextSibling;
	

	if (sibling) {
		var parent=newnode.parentNode;
		parent.appendChild(node);

	  

	}else{
    var parent=newnode.parentNode;
	parent.insertBefore(node,sibling);
  } 

}
// 把一个元素node放到父元素parent的第一个元素前面；
// 获取父元素的第一个元素

function appendBefore(parent,node ){
	var first=firstChild(parent);
	parent.insertBefore(node,first);
}

function appendAfter(parent,node ){
	parent.appendChild(node);
}





// 跳动的线
function xian(obj){
var top=$('.top',obj)[0];
var bottom=$('.bottom',obj)[0];
var left=$('.left',obj)[0];
var right=$('.right',obj)[0];	
obj.onmouseover=function(){
	animate(top,{width:obj.offsetWidth});
	animate(left,{height:obj.offsetHeight});
	animate(bottom,{width:obj.offsetWidth});
	animate(right,{height:obj.offsetHeight})

};
 obj.onmouseout=function(){
	animate(top,{width:0});
	animate(left,{height:0});
	animate(bottom,{width:0});
	animate(right,{height:0});

  }
 
// }

}


















