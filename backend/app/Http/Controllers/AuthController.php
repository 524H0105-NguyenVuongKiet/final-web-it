<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * ĐĂNG KÝ TÀI KHOẢN (Tiêu chí 2.0 điểm)
     */
    public function register(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $request->validate([
            'display_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Tạo User & Băm mật khẩu (Tiêu chí Security)
        $user = User::create([
            'display_name' => $request->display_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'activation_token' => Str::random(60),
            'is_activated' => false,
        ]);

        // 3. Gửi Mail kích hoạt qua Mailtrap (Tiêu chí 0.25 điểm)
        try {
            Mail::to($user->email)->send(new VerifyEmail($user));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng ký xong nhưng chưa gửi được mail. Kiểm tra lại Mailtrap!',
                'error' => $e->getMessage()
            ], 201);
        }

        return response()->json([
            'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.'
        ], 201);
    }

    /**
     * KÍCH HOẠT TÀI KHOẢN (Tiêu chí Activation Link)
     */
    public function verify($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Mã kích hoạt không hợp lệ hoặc đã hết hạn!'], 404);
        }

        $user->is_activated = true;
        $user->activation_token = null; // Xóa token sau khi dùng xong để bảo mật
        $user->save();

        return response()->json(['message' => 'Kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.']);
    }

    /**
     * ĐĂNG NHẬP (Tiêu chí lấy Token làm Auto-save)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Kiểm tra tài khoản và mật khẩu
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác!'], 401);
        }

        // Kiểm tra xem đã nhấn link kích hoạt trong Mailtrap chưa
        if (!$user->is_activated) {
            return response()->json(['message' => 'Tài khoản của bạn chưa được kích hoạt từ Email!'], 403);
        }

        // Tạo Token Sanctum (Chìa khóa để lưu ghi chú tự động)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'display_name' => $user->display_name,
                'email' => $user->email
            ]
        ]);
    }
}