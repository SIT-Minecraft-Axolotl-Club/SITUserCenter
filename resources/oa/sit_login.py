# 文件名：resources/oa/sit_login.py

import requests
import sys
import re
import json
from Crypto.Cipher import AES
import base64
import random
import string

def random_str(length):
    chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'
    return ''.join(random.choices(chars, k=length))

def encrypt_password(password, salt):
    random_prefix = random_str(64)
    iv = random_str(16).encode()
    data = (random_prefix + password).encode()

    cipher = AES.new(salt.encode(), AES.MODE_CBC, iv)
    encrypted = cipher.encrypt(data.ljust((len(data) + 15) // 16 * 16, b'\0'))
    return base64.b64encode(encrypted).decode()

def main():
    if len(sys.argv) < 5:
        print(json.dumps({"success": False, "message": "参数不足"}))
        return

    username = sys.argv[1]
    password = sys.argv[2]
    captcha = sys.argv[3]
    cookie = sys.argv[4]

    headers = {
        'Cookie': cookie,
        'User-Agent': 'Mozilla/5.0'
    }

    # 获取登录页面
    resp = requests.get('https://authserver.sit.edu.cn/authserver/login', headers=headers)
    if resp.status_code != 200:
        print(json.dumps({"success": False, "message": "获取登录页失败"}))
        return

    salt_match = re.search(r'id="pwdDefaultEncryptSalt" value="(.*?)"', resp.text)
    if not salt_match:
        print(json.dumps({"success": False, "message": "未找到 salt"}))
        return

    salt = salt_match.group(1)
    hidden_fields = dict(re.findall(r'<input type="hidden" name="(.*?)" value="(.*?)"', resp.text))

    hidden_fields.update({
        'username': username,
        'password': encrypt_password(password, salt),
        'captchaResponse': captcha
    })

    # 提交登录请求
    login_resp = requests.post(
        'https://authserver.sit.edu.cn/authserver/login',
        headers={
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cookie': cookie,
            'User-Agent': 'Mozilla/5.0'
        },
        data=hidden_fields
    )

    if 'auth_username' in login_resp.text or '安全退出' in login_resp.text:
        print(json.dumps({"success": True}))
    else:
        print(json.dumps({"success": False, "message": "登录失败，可能是密码或验证码错误"}))

if __name__ == '__main__':
    main()
