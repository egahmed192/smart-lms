"""
Test all LMS Integration API endpoints and capture responses for the report.
Uses same base URL and credentials as client's test_api.py.
"""
import json
import requests

BASE_URL = "https://arafa.online"
USER = "lms_user"
PASSWORD = "1"

# Store all responses for report generation
results = {}

session = requests.Session()


def rpc_post(path, payload):
    """Send JSON-RPC POST and return (response_json, None) or (None, error_msg)."""
    try:
        resp = session.post(
            f"{BASE_URL}{path}",
            json=payload,
            headers={"Content-Type": "application/json"},
            timeout=30,
        )
        resp.raise_for_status()
        return resp.json(), None
    except requests.RequestException as e:
        return None, str(e)
    except json.JSONDecodeError as e:
        return None, f"JSON decode error: {e}"


def main():
    print("=" * 60)
    print("Testing all LMS API endpoints")
    print("=" * 60)

    # --- 1. LOGIN ---
    print("\n[1] POST /api/lms/login")
    login_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {"email": USER, "password": PASSWORD},
        "id": 1,
    }
    data, err = rpc_post("/api/lms/login", login_payload)
    if err:
        results["login"] = {"error": err, "payload": login_payload}
        print(f"  FAIL: {err}")
        return results
    results["login"] = {"response": data, "payload": login_payload}
    print("  OK:", json.dumps(data, indent=2)[:500])

    if not data.get("result", {}).get("success"):
        results["login_failed"] = True
        print("  Login failed; stopping.")
        return results

    # --- 2. STUDENT SEARCH (by national_id - from existing test) ---
    print("\n[2] POST /api/lms/student/search (by national_id)")
    search_nid_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {"national_id": "31901171700812"},
        "id": 2,
    }
    data, err = rpc_post("/api/lms/student/search", search_nid_payload)
    if err:
        results["student_search_national_id"] = {"error": err, "payload": search_nid_payload}
        print(f"  FAIL: {err}")
    else:
        results["student_search_national_id"] = {"response": data, "payload": search_nid_payload}
        print("  OK")

    # --- 2b. STUDENT SEARCH (by name - partial) ---
    print("\n[2b] POST /api/lms/student/search (by name)")
    search_name_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {"name": "ا"},  # Arabic char to get some results
        "id": 3,
    }
    data, err = rpc_post("/api/lms/student/search", search_name_payload)
    if err:
        results["student_search_name"] = {"error": err, "payload": search_name_payload}
        print(f"  FAIL: {err}")
    else:
        results["student_search_name"] = {"response": data, "payload": search_name_payload}
        print("  OK")

    # Get first student id for update test
    student_id = None
    for key in ("student_search_national_id", "student_search_name"):
        r = results.get(key, {}).get("response", {})
        students = r.get("result", {}).get("data", {}).get("students", [])
        if students:
            student_id = students[0].get("id")
            break

    # --- 3. STUDENT UPDATE ---
    print("\n[3] POST /api/lms/student/update")
    update_student_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {
            "id": student_id or 1,
            "lms_username": "test_lms_user",
            "lms_password": "test_pass_123",
        },
        "id": 4,
    }
    data, err = rpc_post("/api/lms/student/update", update_student_payload)
    if err:
        results["student_update"] = {"error": err, "payload": update_student_payload}
        print(f"  FAIL: {err}")
    else:
        results["student_update"] = {"response": data, "payload": update_student_payload}
        print("  OK")

    # --- 4. PARENT SEARCH (no filter to get some results) ---
    print("\n[4] POST /api/lms/parent/search (no filter)")
    parent_search_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {},
        "id": 5,
    }
    data, err = rpc_post("/api/lms/parent/search", parent_search_payload)
    if err:
        results["parent_search"] = {"error": err, "payload": parent_search_payload}
        print(f"  FAIL: {err}")
    else:
        results["parent_search"] = {"response": data, "payload": parent_search_payload}
        print("  OK")

    # Parent search by name
    print("\n[4b] POST /api/lms/parent/search (by name)")
    parent_search_name_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {"name": "ا"},
        "id": 6,
    }
    data, err = rpc_post("/api/lms/parent/search", parent_search_name_payload)
    if err:
        results["parent_search_name"] = {"error": err, "payload": parent_search_name_payload}
        print(f"  FAIL: {err}")
    else:
        results["parent_search_name"] = {"response": data, "payload": parent_search_name_payload}
        print("  OK")

    parent_id = None
    parent_national_id = None
    for key in ("parent_search", "parent_search_name"):
        r = results.get(key, {}).get("response", {})
        parents = r.get("result", {}).get("data", {}).get("parents", [])
        if parents:
            parent_id = parents[0].get("id")
            parent_national_id = parents[0].get("identification_id")
            break

    # --- 5. PARENT UPDATE ---
    print("\n[5] POST /api/lms/parent/update")
    update_parent_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {
            "id": parent_id or 1,
            "lms_username": "test_parent_lms",
            "lms_password": "test_parent_pass",
        },
        "id": 7,
    }
    data, err = rpc_post("/api/lms/parent/update", update_parent_payload)
    if err:
        results["parent_update"] = {"error": err, "payload": update_parent_payload}
        print(f"  FAIL: {err}")
    else:
        results["parent_update"] = {"response": data, "payload": update_parent_payload}
        print("  OK")

    # --- 6. LOGOUT ---
    print("\n[6] POST /api/lms/logout")
    logout_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {},
        "id": 8,
    }
    data, err = rpc_post("/api/lms/logout", logout_payload)
    if err:
        results["logout"] = {"error": err, "payload": logout_payload}
        print(f"  FAIL: {err}")
    else:
        results["logout"] = {"response": data, "payload": logout_payload}
        print("  OK")

    # --- 7. LOGIN FAILURE (wrong password) ---
    print("\n[7] POST /api/lms/login (invalid credentials)")
    bad_login_payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "params": {"email": USER, "password": "wrong_password"},
        "id": 9,
    }
    data, err = rpc_post("/api/lms/login", bad_login_payload)
    if err:
        results["login_invalid"] = {"error": err, "payload": bad_login_payload}
    else:
        results["login_invalid"] = {"response": data, "payload": bad_login_payload}
    print("  OK (expected to fail)")

    return results


if __name__ == "__main__":
    all_results = main()
    with open("endpoint_test_results.json", "w", encoding="utf-8") as f:
        json.dump(all_results, f, ensure_ascii=False, indent=2)
    print("\n" + "=" * 60)
    print("Results saved to endpoint_test_results.json")
    print("=" * 60)
