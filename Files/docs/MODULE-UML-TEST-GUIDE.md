# QuoteMatch — Module UML & Test Guide

Use this with the interactive canvas in Cursor, or paste Mermaid into any viewer (GitHub, mermaid.live).

Base URL (local): `http://127.0.0.1:8000`

---

## 1. System overview (component)

```mermaid
flowchart LR
  Guest --> Job
  Buyer --> Job
  Admin --> Job
  Job --> Freelancer
  Freelancer --> Bid
  Bid --> Buyer
  Buyer --> Project
  Bid --> Project
  Payments --> Project
  Buyer --> Chat
  Freelancer --> Chat
  Project --> Dispute
  Admin --> Dispute
```

| Prefix | Actor | Route file |
|--------|--------|------------|
| `/` | Public / Guest | `routes/web.php` |
| `/post-job/` | Guest buyer | `routes/web.php` |
| `/buyer/` | Buyer | `routes/buyer.php` |
| `/freelancer/` | Provider | `routes/user.php` |
| `/admin/` | Admin | `routes/admin.php` |
| `/ipn/` | Gateways | `routes/ipn.php` |

---

## 2. Core marketplace flow (activity)

```mermaid
flowchart LR
  Post[Post job] --> Approve[Admin approve]
  Approve --> Bid[Provider bids]
  Bid --> Hire[Buyer hires]
  Hire --> Upload[Upload work]
  Upload --> Done[Complete + pay]
```

### Entity states to verify

| Entity | States |
|--------|--------|
| Job | Draft → Pending → Approved → Processing |
| Bid | Pending → Shortlisted / Rejected / Withdrawn / Accepted |
| Project | Running → Buyer review → Completed \| Reported |
| Dispute | Open → In review → Resolved |
| TrialTask | Draft → Pending → Accepted → Submitted → Finished |

### Priority E2E order

1. Post → Approve → Bid → Hire → Deliver → Complete  
2. Escrow: deposit → hire → release  
3. Trial task full cycle  
4. Lead credits buy → deduct → block at zero  
5. Project report → admin dispute resolve  
6. Verification upload → admin approve → badge  
7. Dual auth sessions do not mix  
8. Support ticket create → admin reply  

---

## 3. Per-module UML + QA

### Public site

```mermaid
flowchart TD
  Visitor --> Home["/"]
  Home --> Jobs["/freelance-jobs"]
  Home --> Talents["/talents"]
  Jobs --> JobDetail["/explore-job/{slug}"]
```

**Test:** home sections; job list/filter; job detail; talents; contact/cookie pages.

---

### Guest job post

```mermaid
flowchart TD
  Guest --> Wizard["/post-job"]
  Wizard --> Prefs[Preferences]
  Prefs --> Budget[Budget]
  Budget --> Success[Success + account]
```

**Test:** logout → Post Job at 0%; category filters skills; unique slug; logged-in buyer redirected to `/buyer/job/post/*`.

---

### Buyer portal

```mermaid
flowchart TD
  Login["/buyer/login"] --> Dash["/buyer/dashboard"]
  Dash --> Post["Post job wizard"]
  Dash --> Bids[View bids]
  Bids --> Hire[Hire]
  Hire --> Project[Buyer project]
  Dash --> Deposit[Deposit]
```

**Test:** wizard (no slug); hire path; deposit; complete project; bid bell notifications.

---

### Freelancer portal

```mermaid
flowchart TD
  Login["/freelancer/login"] --> Dash["/freelancer/dashboard"]
  Dash --> Browse[Browse jobs]
  Browse --> Bid[Submit quote]
  Bid --> Credits[Lead credit deduct]
  Hire --> Upload["POST /project/upload/{id}"]
```

**Test:** approved to bid; credit deduct; upload URL is `upload` not `upload-form`.

---

### Admin panel

```mermaid
flowchart TD
  Admin --> Jobs[Approve jobs]
  Admin --> Verify[Provider verifications]
  Admin --> Disputes[Resolve disputes]
  Admin --> Money[Deposits / withdrawals]
  Admin --> Skills[Skills + category_id]
```

**Test:** pending job approve; verification queue; dispute resolve; skill category binding.

---

### Auth (3 guards)

```mermaid
flowchart LR
  Guest --> BuyerGuard[Buyer]
  Guest --> UserGuard[Freelancer]
  Guest --> AdminGuard[Admin]
```

**Test:** register + verify each; sessions do not cross; forgot password each portal.

---

### Payments

```mermaid
flowchart LR
  Deposit --> Balance
  Hire --> Escrow
  Complete --> Release
  Withdraw --> AdminApprove
```

**Test:** deposit balance; hire escrow; complete release; withdraw approval; manual gateway locally.

---

### Chat

```mermaid
flowchart LR
  Buyer <--> Conversation
  Freelancer <--> Conversation
```

**Test:** open from bids; send both sides; unread badge; toast when not on chat page.

---

### Bids / quotes

```mermaid
flowchart TD
  Freelancer -->|BID_PLACED| Buyer
  Buyer -->|hire / reject| Bid
  Freelancer -->|BID_WITHRAWN| Buyer
```

**Test:** place → notify; update within limit; withdraw notify; hire rejects others.

---

### Projects

```mermaid
stateDiagram-v2
  [*] --> Running: hire
  Running --> BuyerReview: upload
  BuyerReview --> Completed: buyer complete
  Running --> Reported: report
  BuyerReview --> Reported: report
```

**Test:** RUNNING → upload → BUYER_REVIEW → COMPLETED + pay; report creates dispute.

---

### Disputes

```mermaid
flowchart LR
  Report --> Open
  Open --> InReview
  InReview --> Resolved
```

**Test:** report with type/reason; admin in-review → resolve; parties see outcome.

---

### Notifications

```mermaid
flowchart TD
  Event -->|notify| Log[notification_logs]
  Log --> Bell[Header unread]
  Bell --> Index[Mark read on open]
```

**Test:** new bid badge; mark read; templates; withdraw uses `BID_WITHRAWN`.

---

### Verification badges

```mermaid
flowchart LR
  Upload --> Pending
  Pending --> Approved
  Approved --> PublicBadge
```

**Test:** upload docs; admin approve/reject; badge on public profile.

---

### Lead credits

```mermaid
flowchart LR
  BuyPackage --> Credits
  Bid --> Deduct
  Zero --> BlockBid
```

**Test:** enable monetisation; buy package; deduct on bid; zero blocks bid.

---

### Trial tasks

```mermaid
flowchart LR
  Create --> Accept
  Accept --> Upload
  Upload --> Finish
```

**Test:** create from bid; accept + upload; complete/cancel; admin view.

---

### Support tickets

```mermaid
flowchart LR
  UserOpen --> AdminReply
  AdminReply --> UserReply
  UserReply --> Close
```

**Test:** open with attachment; admin reply; close.

---

### CMS / frontend

```mermaid
flowchart LR
  AdminEdit --> FrontendSections
  FrontendSections --> PublicHome
```

**Test:** edit section → home updates; custom page + SEO.

---

## Smoke URL cheat sheet

```
/
/post-job
/buyer/login
/buyer/dashboard
/buyer/job/post/job-details
/freelancer/login
/freelancer/dashboard
/freelancer/bid/list
/freelancer/project/index
/admin
/admin/jobs/pending
/admin/disputes
/admin/provider-verifications
/admin/monetisation
```
