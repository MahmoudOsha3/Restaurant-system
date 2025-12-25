<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>
</head>
<body>
    <form action="{{ route('test.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>اسم الوجبة</label>
                <input type="text" name="title">
            </div>
            <div class="form-group">
                <label>القسم</label>
                <select name="category_id">
                    @foreach (\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group full-width">
                <label>وصف الوجبة</label>
                <textarea name="description" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>السعر الأساسي</label>
                <input type="number" name="price">
            </div>
            <div class="form-group">
                <label>السعر التخفيضي</label>
                <input type="number" name="compare_price">
            </div>
            <div class="form-group">
                <label>وقت التحضير (دقيقة)</label>
                <input type="number" name="preparation_time" id="preparation_time">
            </div>
            <div class="form-group">
                <label>الحالة</label>
                <select name="status">
                    <option selected value="active">نشط</option>
                    <option value="inactive">مخفي</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label>صورة الوجبة</label>
                <input type="file" name="image">
            </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2" >حفظ البيانات</button>
            <button  style="flex:1; border:none; border-radius:8px; cursor:pointer">إلغاء</button>
        </div>
    </form>
</body>
</html>

