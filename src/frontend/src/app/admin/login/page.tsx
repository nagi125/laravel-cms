"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { ApiRequestError } from "@/lib/api/client";
import { login } from "@/lib/api/auth";

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [message, setMessage] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setMessage(null);
    setErrors({});
    setIsSubmitting(true);

    try {
      await login({ email, password });
      router.replace("/admin/posts");
    } catch (error) {
      if (error instanceof ApiRequestError) {
        setMessage(error.message);
        setErrors(error.errors ?? {});
      } else {
        setMessage("ログインに失敗しました。");
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <section className="mx-auto max-w-md">
      <h1 className="text-3xl font-bold">ログイン</h1>
      <form className="mt-6 space-y-5" onSubmit={handleSubmit}>
        {message ? (
          <p className="text-sm text-red-700" role="alert">
            {message}
          </p>
        ) : null}
        <label className="block">
          メールアドレス
          <input
            autoComplete="email"
            className="mt-1 block w-full rounded border border-slate-300 p-2"
            onChange={(event) => setEmail(event.target.value)}
            required
            type="email"
            value={email}
          />
        </label>
        {errors.email?.[0] ? <p className="text-sm text-red-700">{errors.email[0]}</p> : null}
        <label className="block">
          パスワード
          <input
            autoComplete="current-password"
            className="mt-1 block w-full rounded border border-slate-300 p-2"
            onChange={(event) => setPassword(event.target.value)}
            required
            type="password"
            value={password}
          />
        </label>
        {errors.password?.[0] ? <p className="text-sm text-red-700">{errors.password[0]}</p> : null}
        <button
          className="rounded bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
          disabled={isSubmitting}
          type="submit"
        >
          {isSubmitting ? "ログイン中..." : "ログイン"}
        </button>
      </form>
    </section>
  );
}
