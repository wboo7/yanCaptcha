<?php
/**
 * @link http://www.yanphp.com/
 * @copyright Copyright (c) 2016 YANPHP Software LLC
 * @license http://www.yanphp.com/license/
 */

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0">
    <title>Title</title>
    <link rel="stylesheet" href="../../statics/bootstrap.css">
</head>
<body>
<style>
    .form{
        width: 300px;
        margin: 100px auto;
    }
    .btn_send{
        cursor: pointer;
    }
</style>
<div class="form">
    <form id="my_form" method="post" action="post.php">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">手机</div>
                <input class="form-control" type="text" name="mobile" value="">
            </div>

        </div>

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">密码</div>
                <input class="form-control" type="password" name="password" value="">
            </div>
        </div>


        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="code">
                <div class="input-group-addon"><span id="btn_send">获取手机验证码</span></div>

            </div>


        </div>

        <div class="form-group">
            <div id="myCaptcha"></div>
        </div>

        <div>
            <button style="width: 100%" class="btn btn-default" id="btn_submit">立即注册</button>
        </div>

    </form>
</div>
<script src="../../statics/jquery.js"></script>
<script src="../../statics/bootstrap.min.js"></script>
<script src="http://api.yanphp.com/statics/dun/v1/core.min.js"></script>

<script>
    initYANCaptcha({
        app_id:'100012',
        selector:'#myCaptcha',
        pattern:'modal', // toggle static popup
        type:'slide',
        modal_effect:'bounceIn',
        bind_form:'#my_form',
        width:300,
        api_server:'api.yanphp.com'
    },function(instance){
        var valid = false;
        instance.onValidate(function(result) {
            //.验证操作执行后
            if(result.status == 'success')
            {
                valid = true;
            }
        });
        instance.onReady(function() {
            //.验证码文档渲染完毕
        });

        function check() {
            var code = $('[name="code"]').val();
            var mobile = $('[name="mobile"]').val();
            var password = $('[name="password"]').val();
            if(code == '' || mobile == '' || password == '')
            {
                return false;
            }
            return true;
        }

        $('#btn_submit').click(function (e) {
            if(!check())
            {
                if(!valid)
                {
                    e.preventDefault();
                    instance.verify();
                }
            }
            else
            {
                alert('所有验证都已经完毕，可以提交表单了')
            }

        });
        //.获取手机验证码
        $('#btn_send').click(function (e) {
            var $this = $(this);
            var text = $this.text();

            if($this.hasClass('disabled')) return;

            //.第一步检查是否输入手机
            var mobile = $('[name="mobile"]').val();
            if(mobile == '')
            {
                alert('请输入手机号码');
                return;
            }
            //.第二部检查是否验证
            var validate = instance.getValidate();
            if(!validate)
            {
                instance.verify();
                return;
            }
            //.第三步发送手机短信
            $.ajax({
                url:'send.php',//短信发送地址
                method:'post',
                dataType:'html',
                data:{validate:validate,mobile:mobile},
                beforeSend:function () {
                    $this.addClass('disabled');
                },
                success:function (resp) {
                     if(resp == 'success')
                     {
                         var count = 60;
                         var handle;
                         $this.addClass('disabled');
                         handle = setInterval(function(){
                             if(count==0)
                             {
                                 count = 60;
                                 clearInterval(handle);
                                 $this.removeClass('disabled');
                                 $this.text(text);
                             }
                             else
                             {
                                 $this.text(count+'秒后重新获取');
                                 count--;
                             }
                         },1000);
                     }
                     else
                     {
                         $this.removeClass('disabled');
                         alert('发送失败');
                     }
                }
            })
        })
    });

</script>
</body>
</html>

