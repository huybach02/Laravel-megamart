// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ["resources/css/app.css", "resources/js/app.js"],
//             refresh: [
//                 "resources/routes/**",
//                 "routes/**",
//                 "resources/views/**",
//                 "resources/css/**",
//                 "resources/js/**",
//             ],
//         }),
//     ],
// });

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "public/backend/assets/css/skins/style.css", // Đường dẫn chính xác đến file CSS
            ],
            refresh: [
                "resources/css/**",
                "resources/js/**",
                "resources/views/**",
                "routes/**",
                "public/backend/assets/css/**", // Thêm đường dẫn thư mục chứa file CSS để theo dõi
            ],
        }),
    ],
});
