<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動報名表</title>
    </head>
    <body>
        <h1>活動報名表</h1>
        <from action="">
    


        <fieldset>
        <legend>基本資料</legend>
        <p>
            <label for="name">姓名</label>
            <input type="text" name="name" id="name" value="" placeholder="請用中文" required>
        </p>
        <p>
            <label for="">性別</label>
            <input type="radio" name="gender" id="gender1" value="1">
            <label for="gender1">男生</label>
            <input type="radio" name="gender" id="gender2" value="2">
            <label for="gender2">女生</label>
        </p>

        <p>
            <label for="bday">生日</label>
            <input type="date" name="bday" id="bday" value="<?=date("Y-m-d")?>">
        </p>

        <p>
             <label for="phone">電話</label>
            <input type="text" name="phone" id="phone">
         </p>

         <p>
        <lable for="area">居住區域</lable>
        <select name="area" id="area">
            <option value="0">請選擇...</option>
            <option value="1">北部</option>
            <option value="2">中部</option>
            <option value="3">南部</option>
            <option value="4">東部</option>
            <option value="5">外島</option>
        </select>
     </p>
    </fieldset>  


        <fieldset>  
         <legend>使用行為</legend>
        <input type="checkbox" name="behavior[]" id="behavior1" >
        <lable for="behavior1">聊天</lable>
        <input type="checkbox" name="behavior[]" id="behavior2" >
        <lable for="behavior2">直播</lable>
        <input type="checkbox" name="behavior[]" id="behavior3" >
        <lable for="behavior3">書信</lable>
        <input type="checkbox" name="behavior[]" id="behavior4" >
        <lable for="behavior4">社群</lable>
        <input type="checkbox" name="behavior[]" id="behavior5" >
        <lable for="behavior5">購物</lable>
        <input type="checkbox" name="behavior[]" id="behavior6" >
        <lable for="behavior6">金融</lable>


    </fieldset>

    <fieldset>  
     <legend>滿意度</legend>
        <label for="">場地</label>
        <input type="radio" name="place" id="place5" value="5">
        <label for="place5">非常滿意</label>
        <input type="radio" name="place" id="place4" value="4">
        <label for="place4">滿意</label>
        <input type="radio" name="place" id="place3" value="3">
        <label for="place3">普通</label>
        <input type="radio" name="place" id="place2" value="2">
        <label for="place2">不滿意</label>
        <input type="radio" name="place" id="place1" value="1">
        <label for="place1">非常不滿意</label>

        <p>
        <label for="">設備</label>
        <input type="radio" name="facility" id="facility5" value="5">
        <label for="facility5">非常滿意</label>
        <input type="radio" name="facility" id="facility4" value="4">
        <label for="facility4">滿意</label>
        <input type="radio" name="facility" id="facility3" value="3">
        <label for="facility3">普通</label>
        <input type="radio" name="facility" id="facility2" value="2">
        <label for="facility2">不滿意</label>
        <input type="radio" name="facility" id="facility1" value="1">
        <label for="facility1">非常不滿意</label>
        </p>

        <p>
        <label for="">服務</label>
        <input type="radio" name="service" id="service5" value="5">
        <label for="service5">非常滿意</label>
        <input type="radio" name="service" id="service4" value="4">
        <label for="service4">滿意</label>
        <input type="radio" name="service" id="service3" value="3">
        <label for="service3">普通</label>
        <input type="radio" name="service" id="service2" value="2">
        <label for="service2">不滿意</label>
        <input type="radio" name="service" id="service1" value="1">
        <label for="service1">非常不滿意</label>
        </p>
            
    </fieldset>  

        <fieldset>  
         <legend>資料上傳</legend>

         <p>
           <label for="">同意書</label>
           <input type="file" name="agreement" id="agreement" accept=".pdf,.doc,.docx">
         </p>

         <p>
           <label for="image">個人照片</label>
           <input type="file" name="image" id="image" accept="image/*" onchange="prveiew_image(event)">
           <div><img id="output_image" width="300"></div>
         </p>

        </fieldset> 

        <input type="submit" value="送出">
    </from>
     
    <script type='text/javascript'>
function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('output_image');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>