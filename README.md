# CJdropshipping OAuth 2.0 Integration (SaaS Platform)

This repository contains the technical implementation of the **CJdropshipping Partner Authorization** workflow. It is designed for multi-tenant SaaS architectures to securely authorize user stores and manage automated e-commerce operations.

## 🏗 Architecture Overview

The integration is built using a structured **Service-Controller** pattern to handle the complex OAuth lifecycle required by CJdropshipping:

* **Controllers**: Manage the initial user redirection to the CJ login page and process the incoming callback parameters.
* **Services**: Handle backend-to-backend communication for exchanging the `apiKey`, managing token persistence, and refreshing credentials.
* **Models**: DTOs (Data Transfer Objects) mapped to CJ's response structures, including `openId`, `accessToken`, and `quotaLimits`.

## 🔐 Authorization Workflow

### 1. Initiate Authorization
The controller initiates the process by redirecting users to the official CJ authorization portal:
* **Production URL**: `https://www.cjdropshipping.com/mine/authorize/erpAuthorization`.
* **Required Fields**: `clientId`, `parnterName`, and a URL-encoded `clientRedirectUrl`.

### 2. The Token "Handshake"
Upon user approval, CJ redirects back to your platform with an `apiKey`.
> [!IMPORTANT]
> You **must** immediately call the `getAccessToken` endpoint upon receiving the `apiKey`. Failure to complete this step leaves the authorization in an "Invalid" state within the CJ system.

### 3. Token Lifecycle Management
* **Access Token**: Used in the `CJ-Access-Token` header for all functional API calls.
* **Refresh Token**: Used to obtain new tokens when the current Access Token expires.
* **Proactive Refresh**: The service logic is designed to refresh tokens **48 hours before expiration** to ensure uninterrupted API access.

## 🚦 Technical Implementation Details

### Platform Mode & QPS
By utilizing **Platform Authorization Mode**, the system provides enhanced scalability:
* **Header Configuration**: Including the `platformToken` (your platform account's token) lifts individual user API call count limits.
* **Shared QPS**: All customers under the platform share the platform account’s QPS (Queries Per Second) quota.
* **Concurrency**: Critical endpoints (Orders/Payments) maintain strict concurrency limits to ensure inventory integrity.

### Webhooks & Tenant Isolation
* **OpenID Identification**: The `openId` returned during the handshake is used to distinguish which user store an event belongs to.
* **Centralized Listeners**: The platform account uses its own Webhook URL to receive updates for `product`, `stock`, `order`, and `logistics` for all authorized users.

## 🛠 API Implementation Map

| Endpoint Function | Path | Description |
| :--- | :--- | :--- |
| **Get Access Token** | `/v1/authentication/getAccessToken` | Exchanges `apiKey` for the initial token set. |
| **Refresh Token** | `/v1/authentication/refreshAccessToken` | Uses `refreshToken` to rotate credentials. |
| **Get Settings** | `/v1/setting/get` | Retrieves `openId`, quota limits, and sandbox status. |
| **Logout** | `/v1/authentication/logout` | Immediately invalidates the current Access Token. |

## ⚠️ Security Best Practices
* **Referrer Policy**: The callback page includes `<meta name="referrer" content="no-referrer">` to prevent `apiKey` leakage via HTTP headers.
* **Credential Storage**: `apiKey`, `Access Token`, and `Refresh Token` are stored securely on the backend and never exposed to client-side logs or JavaScript.

---

