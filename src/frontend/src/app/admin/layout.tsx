"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { ApiRequestError } from "@/lib/api/client";
import { logout, me } from "@/lib/api/auth";

export default function AdminLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  const pathname = usePathname();
  const router = useRouter();
  const [isLoggingOut, setIsLoggingOut] = useState(false);
  const isLoginPage = pathname === "/admin/login";

  useEffect(() => {
    if (isLoginPage) {
      return;
    }

    void me().catch((error: unknown) => {
      if (error instanceof ApiRequestError && error.status === 401) {
        router.replace("/admin/login");
      }
    });
  }, [isLoginPage, router]);

  const handleLogout = async () => {
    setIsLoggingOut(true);
    try {
      await logout();
    } finally {
      router.replace("/admin/login");
      setIsLoggingOut(false);
    }
  };

  if (isLoginPage) {
    return <>{children}</>;
  }

  return (
    <section>
      <div className="mb-8 flex items-center justify-between border-b border-slate-200 pb-4">
        <Link className="font-semibold" href="/admin/posts">
          管理画面
        </Link>
        <button
          className="text-sm text-slate-600 hover:underline disabled:opacity-50"
          disabled={isLoggingOut}
          onClick={handleLogout}
          type="button"
        >
          {isLoggingOut ? "ログアウト中..." : "ログアウト"}
        </button>
      </div>
      {children}
    </section>
  );
}
