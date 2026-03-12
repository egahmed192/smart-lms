import requests

BASE_URL = "https://arafa.online"
USER = 'lms_user'
PASSWORD='1'

session = requests.Session()

def rpc_post(path, payload):
    """Helper to send JSON-RPC and return parsed JSON or raise for HTTP."""
    resp = session.post(
        f"{BASE_URL}{path}",
        json=payload,
        headers={"Content-Type": "application/json"},
        timeout=30,
    )
    resp.raise_for_status()
    return resp.json()

# Login
login_payload = {
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "email": USER,
        "password": PASSWORD
    },
    "id": 1
}
login_data = rpc_post("/api/lms/login", login_payload)
print("Login response:", login_data)

if not login_data.get("result", {}).get("success"):
    raise RuntimeError(f"Login failed: {login_data}")

# Search student
search_payload = {
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "national_id": "31901171700812"
    },
    "id": 2
}
search_data = rpc_post("/api/lms/student/search", search_payload)
print("Search response:", search_data)

try:
    student_id = search_data['result']['data']['students'][0]['id']
except Exception:
    raise RuntimeError("No students returned from search; cannot proceed with update")

# Update student LMS credentials
update_payload = {
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "id": student_id,
        "lms_username": "EHAB",
        "lms_password": "eeee"
    },
    "id": 3
}
update_data = rpc_post("/api/lms/student/update", update_payload)
print("Update response:", update_data)
