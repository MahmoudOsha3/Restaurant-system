<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل الوجبة | شيخ المندي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Reem+Kufi:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site/style.css') }}"> <style>
        .product-details-container {
            padding: 150px 8% 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            min-height: 80vh;
        }
        .product-image img {
            width: 100%;
            border-radius: 30px;
            border: 2px solid var(--gold);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .product-info h1 {
            font-family: 'Reem Kufi';
            font-size: 3.5rem;
            color: var(--gold);
            margin-bottom: 20px;
        }
        .product-meta {
            margin: 20px 0;
            padding: 20px;
            background: var(--light-dark);
            border-radius: 15px;
        }
        .ingredients {
            margin-top: 30px;
        }
        .ingredients h3 { color: var(--gold); margin-bottom: 15px; }
        .ingredients ul { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .ingredients li i { color: var(--primary); margin-left: 10px; }

        .action-area {
            margin-top: 40px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            background: #222;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .quantity-selector button {
            background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;
        }
        .quantity-selector span { margin: 0 20px; font-weight: bold; font-size: 1.2rem; }
    </style>
</head>
<body>
    <section class="product-details-container">
        <div class="product-image animate-up">
            <img src="https://images.unsplash.com/photo-1544124499-58912cbddaad?w=800" alt="صينية السلطان">
        </div>

        <div class="product-info animate-up">
            <h1>صينية السلطان</h1>
            <div class="product-meta">
                <span class="price" style="font-size: 2rem;">1800 ج.م</span>
                <p style="margin-top: 10px; color: #aaa;">تكفي لـ 6-8 أشخاص | وقت التحضير: 45 دقيقة</p>
            </div>

            <div class="ingredients">
                <h3>المكونات والملحقات:</h3>
                <ul>
                    <li><i class="fas fa-check"></i> نصف تيس بلدي طازج</li>
                    <li><i class="fas fa-check"></i> أرز بشاور ملكي بالمكسرات</li>
                    <li><i class="fas fa-check"></i> 4 أنواع سلطات (دقوس، زبادي...)</li>
                    <li><i class="fas fa-check"></i> طبق مقبلات مشكلة كبير</li>
                </ul>
            </div>

            <div class="action-area">
                <div class="quantity-selector">
                    <button>-</button>
                    <span>1</span>
                    <button>+</button>
                </div>
                <button class="btn-main" style="width: 100%;">إضافة إلى السلة</button>
            </div>
        </div>
    </section>

    </body>
</html>
