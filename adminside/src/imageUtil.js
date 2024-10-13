export const imageUrl = (thumbnail) => {
    const baseUrl = import.meta.env.VITE_APP_URL || 'http://localhost:8000';
    return `${baseUrl}/storage/${thumbnail}`;
};
